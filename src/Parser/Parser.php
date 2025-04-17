<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\TokenOutOfBoundsException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\Node\Function\ArgumentCountException;
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
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Operator\Comparison\All;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Any;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Operator\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Operator\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Has;
use olml89\ODataParser\Parser\Node\Operator\Comparison\In;
use olml89\ODataParser\Parser\Node\Operator\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Operator\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Operator\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Operator\Logical\AndOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\NotOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\OrOperator;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\PropertyTree;
use olml89\ODataParser\Parser\Node\Value\CastingException;
use olml89\ODataParser\Parser\Node\Value\Value;

final readonly class Parser
{
    private TokenManager $tokens;

    public function __construct(Token ...$tokens)
    {
        $this->tokens = new TokenManager(...$tokens);
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
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
     * @throws CastingException
     */
    private function parseOr(): Node
    {
        $node = $this->parseAnd();

        while ($this->tokens->peek()->consume(TokenKind::Or)) {
            $right = $this->parseAnd();
            $node = new OrOperator($node, $right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseAnd(): Node
    {
        $node = $this->parseComparison();

        while ($this->tokens->peek()->consume(TokenKind::And)) {
            $right = $this->parseComparison();
            $node = new AndOperator($node, $right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
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
     * @throws CastingException
     */
    private function parseNot(): Node
    {
        if ($this->tokens->peek()->consume(TokenKind::Not)) {
            if ($this->tokens->peek()->consume(TokenKind::OpenParen)) {
                $subExpression = $this->parseOr();
                $this->tokens->peek()->expect(TokenKind::CloseParen);

                return new NotOperator($subExpression);
            }

            return new NotOperator($this->parseComparison());
        }

        return $this->parseArithmetic();
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseArithmetic(): Node
    {
        /**
         * The unary arithmetic takes precedence
         */
        if ($this->tokens->peek()->consume(TokenKind::Minus)) {
            return new Minus($this->parseExpression());
        }

        $node = $this->parseExpression();

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

            $right = $this->parseExpression();
            $node = $next($right);
        }

        return $node;
    }

    /**
     * @throws TokenOutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseExpression(): Node
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

            /** @var Property $property */
            $property = array_shift($arguments);

            return match ($name) {
                FunctionName::concat => Concat::invoke($property, $arguments),
                FunctionName::contains => Contains::invoke($property, $arguments),
                FunctionName::endswith => EndsWith::invoke($property, $arguments),
                FunctionName::indexof => IndexOf::invoke($property, $arguments),
                FunctionName::length => new Length($property),
                FunctionName::matchesPattern => MatchesPattern::invoke($property, $arguments),
                FunctionName::startswith => StartsWith::invoke($property, $arguments),
                FunctionName::substring => SubString::invoke($property, $arguments),
                FunctionName::tolower => new ToLower($property),
                FunctionName::toupper => new ToUpper($property),
                FunctionName::trim => new Trim($property),
            };
        }

        /**
         * Primaries (properties, values)
         */
        $valueToken = $peek->valueToken();

        $primary = match (true) {
            $peek->consume(TokenKind::Identifier),  => new Property($valueToken->value),
            $peek->consume(TokenKind::Null),
            $peek->consume(TokenKind::Boolean),
            $peek->consume(TokenKind::Number),
            $peek->consume(TokenKind::String),      => new Literal(Value::fromValueToken($valueToken)),
            default                                             => null,
        };

        /**
         * Collection operators (any, all)
         *
         * OData nested properties, which represent structures like $entity->property->property in PHP.
         */
        if ($primary instanceof Property) {
            $propertyTree = new PropertyTree($primary);

            /**
             * The OData protocol defines they are expressed with slashes separating each nested property, for example:
             * order/items/any(i: i/invoice/amount gt 100 and i/status eq 'open')
             */
            while ($this->tokens->peek()->consume(TokenKind::Slash)) {
                $next = $this->tokens->peek();

                if ($next->consume(TokenKind::Any, TokenKind::All)) {
                    $property = $propertyTree->build();
                    $this->tokens->peek()->expect(TokenKind::OpenParen);

                    $variable = new Property(
                        $this->tokens->peek()->expect(TokenKind::Identifier)->valueToken()->value,
                    );

                    $this->tokens->peek()->expect(TokenKind::Colon);
                    $predicate = $this->parseOr();
                    $this->tokens->peek()->expect(TokenKind::CloseParen);

                    $collectionLambda = match ($next->token->kind) {
                        TokenKind::Any => new Any($property, $variable, $predicate),
                        TokenKind::All => new All($property, $variable, $predicate),
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

                $propertyTree->addSubProperty(
                    new Property($next->valueToken()->value)
                );
            }

            return $propertyTree->build();
        }

        return $primary ?? throw UnexpectedTokenException::position($peek->token, $peek->position);
    }
}
