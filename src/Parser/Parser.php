<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\ArgumentCountException;
use olml89\ODataParser\Parser\Exception\CastingException;
use olml89\ODataParser\Parser\Exception\LiteralTypeException;
use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Exception\TokenOutOfBoundsException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\ToUpper;
use olml89\ODataParser\Parser\Node\Function\Trim;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Expression\Comparison\All;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Has;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\ScalarCaster;

final readonly class Parser
{
    private TokenManager $tokens;
    private ScalarCaster $scalarCaster;

    public function __construct(Token ...$tokens)
    {
        $this->tokens = new TokenManager(...$tokens);
        $this->scalarCaster = new ScalarCaster();
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    public function parse(): ?Node
    {
        if ($this->tokens->isEmpty()) {
            return null;
        }

        return $this->parseOr();
    }

    /**
     * The order of precedence of operators in OData makes us parse in this order,
     * from the lowest precedence to highest:
     *
     * parseOr()
     *      parseAnd()
     *          parseComparison()
     *              parseNot()
     *                  parseArithmetic()
     *                      parseExpression()
     *
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parseOr(): Node
    {
        $node = $this->parseAnd();

        while ($this->tokens->peek()->consume(TokenKind::Or)) {
            $right = $this->parseAnd();
            $node = new OrExpression($node, $right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parseAnd(): Node
    {
        $node = $this->parseComparison();

        while ($this->tokens->peek()->consume(TokenKind::And)) {
            $right = $this->parseComparison();
            $node = new AndExpression($node, $right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parseComparison(): Node
    {
        $left = $this->parseNot();

        if ($left instanceof Property && $this->tokens->peek()->consume(TokenKind::In)) {
            $this->tokens->peek()->expect(TokenKind::OpenParen);
            $values = [];

            while (!$this->tokens->peek()->is(TokenKind::CloseParen)) {
                $expression = $this->parseNot();

                if ($expression instanceof Literal) {
                    $values[] = $expression;
                }

                $this->tokens->peek()->consume(TokenKind::Comma);
            }

            $this->tokens->peek()->expect(TokenKind::CloseParen);

            return new In($left, ...$values);
        }

        $comparison = match (true) {
            $this->tokens->peek()->consume(TokenKind::Equal) => new Equal(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::NotEqual) => new NotEqual(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::LessThan) => new LessThan(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::LessThanOrEqual) => new LessThanOrEqual(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::GreaterThan) => new GreaterThan(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::GreaterThanOrEqual) => new GreaterThanOrEqual(
                $left,
                $this->parseNot(),
            ),
            $this->tokens->peek()->consume(TokenKind::Has) => new Has(
                $left,
                $this->parseNot(),
            ),
            default => null,
        };

        return $comparison ?? $left;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parseNot(): Node
    {
        if ($this->tokens->peek()->consume(TokenKind::Not)) {
            if ($this->tokens->peek()->consume(TokenKind::OpenParen)) {
                $subExpression = $this->parseOr();
                $this->tokens->peek()->expect(TokenKind::CloseParen);

                return new NotExpression($subExpression);
            }

            return new NotExpression($this->parseComparison());
        }

        return $this->parseArithmetic();
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parseArithmetic(): Node
    {
        /**
         * The unary arithmetic takes precedence
         */
        if ($this->tokens->peek()->consume(TokenKind::Minus)) {
            return new Minus($this->parseArithmetic());
        }

        $node = $this->parsePrimary();

        if ($this->tokens->eof()) {
            return $node;
        }

        while (true) {
            $next = match (true) {
                $this->tokens->peek()->consume(TokenKind::Mul) => fn (Node $right): Mul => new Mul(
                    $node,
                    $right,
                ),
                $this->tokens->peek()->consume(TokenKind::Div) => fn (Node $right): Div => new Div(
                    $node,
                    $right,
                ),
                $this->tokens->peek()->consume(TokenKind::DivBy) => fn (Node $right): DivBy => new DivBy(
                    $node,
                    $right,
                ),
                $this->tokens->peek()->consume(TokenKind::Mod) => fn (Node $right): Mod => new Mod(
                    $node,
                    $right,
                ),
                $this->tokens->peek()->consume(TokenKind::Add) => fn (Node $right): Add => new Add(
                    $node,
                    $right,
                ),
                $this->tokens->peek()->consume(TokenKind::Sub) => fn (Node $right): Sub => new Sub(
                    $node,
                    $right,
                ),
                default => null,
            };

            if (is_null($next)) {
                break;
            }

            $right = $this->parsePrimary();
            $node = $next($right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     * @throws CastingException
     */
    private function parsePrimary(): Node
    {
        $peek = $this->tokens->peek();

        /**
         * Subexpressions
         */
        if ($peek->consume(TokenKind::OpenParen)) {
            $subExpression = $this->parseOr();
            $this->tokens->peek()->expect(TokenKind::CloseParen);

            return $subExpression;
        }

        /**
         * Functions
         */
        if ($peek->consume(TokenKind::Function)) {
            $name = FunctionName::from($peek->valueToken()->value);
            $this->tokens->peek()->expect(TokenKind::OpenParen);
            $arguments = [];

            if (!$this->tokens->peek()->is(TokenKind::CloseParen)) {
                do {
                    $arguments[] = $this->parseOr();
                } while ($this->tokens->peek()->consume(TokenKind::Comma));
            }

            $this->tokens->peek()->expect(TokenKind::CloseParen);
            $property = array_shift($arguments);

            return match ($name) {
                FunctionName::concat => Concat::invoke($property, $arguments),
                FunctionName::contains => Contains::invoke($property, $arguments),
                FunctionName::endswith => EndsWith::invoke($property, $arguments),
                FunctionName::indexof => IndexOf::invoke($property, $arguments),
                FunctionName::length => Length::invoke($property),
                FunctionName::matchesPattern => MatchesPattern::invoke($property, $arguments),
                FunctionName::startswith => StartsWith::invoke($property, $arguments),
                FunctionName::substring => SubString::invoke($property, $arguments),
                FunctionName::tolower => ToLower::invoke($property),
                FunctionName::toupper => ToUpper::invoke($property),
                FunctionName::trim => Trim::invoke($property),
            };
        }

        /**
         * Primaries (properties, values)
         */
        $valueToken = $peek->valueToken();

        $primary = match (true) {
            $peek->consume(TokenKind::Identifier),  => Property::from($valueToken->value),
            $peek->consume(TokenKind::Null),
            $peek->consume(TokenKind::Boolean),
            $peek->consume(TokenKind::Number),
            $peek->consume(TokenKind::String),      => new Literal($this->scalarCaster->cast($valueToken)),
            default                                             => null,
        };

        /**
         * Collection operators (any, all)
         *
         * OData nested properties, which represent structures like $entity->property->property in PHP.
         */
        if ($primary instanceof Property) {

            /**
             * The OData protocol defines they are expressed with slashes separating each nested property, for example:
             * order/items/any(i: i/invoice/amount gt 100 and i/status eq 'open')
             */
            while ($this->tokens->peek()->consume(TokenKind::Slash)) {
                $next = $this->tokens->peek();

                if ($next->consume(TokenKind::Any, TokenKind::All)) {
                    $this->tokens->peek()->expect(TokenKind::OpenParen);

                    $variable = Property::from(
                        $this->tokens->peek()->expect(TokenKind::Identifier)->valueToken()->value,
                    );

                    $this->tokens->peek()->expect(TokenKind::Colon);
                    $predicate = $this->parseOr();
                    $this->tokens->peek()->expect(TokenKind::CloseParen);

                    $collectionLambda = match ($next->token->kind) {
                        TokenKind::Any => new Any($primary, $variable, $predicate),
                        TokenKind::All => new All($primary, $variable, $predicate),
                        default => null,
                    };

                    return $collectionLambda ?? throw UnexpectedTokenException::wrongTokenKind(
                        $next->token,
                        TokenKind::Any,
                        TokenKind::All,
                    );
                }

                if (!$next->consume(TokenKind::Identifier)) {
                    return $primary;
                }

                $primary = $primary->addSubProperty(
                    Property::from($next->valueToken()->value),
                );
            }

            return $primary;
        }

        return $primary ?? throw UnexpectedTokenException::position($peek->token, $peek->position);
    }
}
