<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Exception\OutOfBoundsException;
use olml89\ODataParser\Parser\Exception\ParserException;
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
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\ArithmeticNode;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Sub;
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
use olml89\ODataParser\Parser\Node\Value\CastingException;
use olml89\ODataParser\Parser\Node\Value\Value;

final class Parser
{
    /**
     * @var Token[]
     */
    private readonly array $tokens;

    private int $position = 0;

    public function __construct(Token ...$tokens)
    {
        $this->tokens = $tokens;
    }

    private function count(): int
    {
        return count($this->tokens);
    }

    private function eof(): bool
    {
        return $this->count() === 0 || $this->position >= $this->count() - 1;
    }

    private function advance(): void
    {
        if ($this->eof()) {
            return;
        }

        ++$this->position;
    }

    /**
     * @throws OutOfBoundsException
     */
    private function peek(): TokenWrapper
    {
        $token = $this->tokens[$this->position] ?? null;

        if (is_null($token)) {
            throw new OutOfBoundsException($this->position, $this->count());
        }

        return new TokenWrapper(
            token: $token,
            advanceTokenPosition: fn () => $this->advance(),
        );
    }

    /**
     * @throws ParserException
     * @throws CastingException
     */
    public function parse(): Node
    {
        $this->position = 0;

        return $this->parseOr();
    }

    /**
     * The order of precedence of operators in OData makes us parse in this order,
     * from the lowest precedence to highest:
     *
     * parseOr()
     *      parseAnd()
     *          parseComparison()
     *              parseArithmetic()
     *                  parseNot()
     *                      parsePrimary() [parentheses, functions, literals]
     *
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseOr(): Node
    {
        $node = $this->parseAnd();

        while ($this->peek()->consume(TokenKind::Or)) {
            $right = $this->parseAnd();
            $node = new OrOperator($node, $right);
        }

        return $node;
    }

    /**
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseAnd(): Node
    {
        $node = $this->parseComparison();

        while ($this->peek()->consume(TokenKind::And)) {
            $right = $this->parseComparison();
            $node = new AndOperator($node, $right);
        }

        return $node;
    }

    /**
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseComparison(): Node
    {
        $left = $this->parseArithmetic();

        if ($left instanceof Property && $this->peek()->consume(TokenKind::In)) {
            $this->peek()->expect(TokenKind::OpenParen);
            $values = [];

            while (!$this->peek()->is(TokenKind::CloseParen)) {
                $expression = $this->parseArithmetic();

                if ($expression instanceof Literal) {
                    $values[] = $expression;
                }

                $this->peek()->consume(TokenKind::Comma);
            }

            $this->peek()->expect(TokenKind::CloseParen);

            return new In($left, ...$values);
        }

        $comparison = match (true) {
            $this->peek()->consume(TokenKind::Equal) => new Equal(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::NotEqual) => new NotEqual(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::LessThan) => new LessThan(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::LessThanOrEqual) => new LessThanOrEqual(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::GreaterThan) => new GreaterThan(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::GreaterThanOrEqual) => new GreaterThanOrEqual(
                $left,
                $this->parseArithmetic(),
            ),
            $this->peek()->consume(TokenKind::Has) => new Has(
                $left,
                $this->parseArithmetic(),
            ),
            default => null,
        };

        return $comparison ?? $left;
    }

    /**
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseArithmetic(): Node
    {
        /**
         * The unary arithmetic takes precedence
         */
        if ($this->peek()->consume(TokenKind::Minus)) {
            return new Minus($this->parseNot());
        }

        $node = $this->parseNot();

        if ($this->eof()) {
            return $node;
        }

        while (true) {
            $next = match (true) {
                $this->peek()->consume(TokenKind::Mul) => fn (Node $right): ArithmeticNode => new Mul(
                    $node,
                    $right
                ),
                $this->peek()->consume(TokenKind::Div) => fn (Node $right): ArithmeticNode => new Div(
                    $node,
                    $right
                ),
                $this->peek()->consume(TokenKind::DivBy) => fn (Node $right): ArithmeticNode => new DivBy(
                    $node,
                    $right
                ),
                $this->peek()->consume(TokenKind::Mod) => fn (Node $right): ArithmeticNode => new Mod(
                    $node,
                    $right
                ),
                $this->peek()->consume(TokenKind::Add) => fn (Node $right): ArithmeticNode => new Add(
                    $node,
                    $right
                ),
                $this->peek()->consume(TokenKind::Sub) => fn (Node $right): ArithmeticNode => new Sub(
                    $node,
                    $right
                ),
                default => null,
            };

            if (is_null($next)) {
                break;
            }

            $right = $this->parseNot();
            $node = $next($right);
        }

        return $node;
    }

    /**
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parseNot(): Node
    {
        if ($this->peek()->consume(TokenKind::Not)) {
            $operand = $this->parseNot();

            return new NotOperator($operand);
        }

        return $this->parsePrimary();
    }

    /**
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
     */
    private function parsePrimary(): Node
    {
        $peek = $this->peek();

        if ($peek->consume(TokenKind::OpenParen)) {
            $subExpression = $this->parseOr();
            $this->peek()->expect(TokenKind::CloseParen);

            return $subExpression;
        }

        if ($peek->token instanceof ValueToken && $peek->consume(TokenKind::Function)) {
            $name = FunctionName::from($peek->token->value);
            $this->peek()->expect(TokenKind::OpenParen);
            $arguments = [];

            if (!$this->peek()->is(TokenKind::CloseParen)) {
                do {
                    $arguments[] = $this->parseOr();
                } while ($this->peek()->consume(TokenKind::Comma));
            }

            $this->peek()->expect(TokenKind::CloseParen);

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

        $primary = !($peek->token instanceof ValueToken) ? null : match (true) {
            $peek->consume(TokenKind::Identifier),  => new Property($peek->token->value),
            $peek->consume(TokenKind::Null),
            $peek->consume(TokenKind::Boolean),
            $peek->consume(TokenKind::Number),
            $peek->consume(TokenKind::String),      => new Literal(Value::fromValueToken($peek->token)),
            default                                             => null,
        };

        return $primary ?? throw UnexpectedTokenException::position($peek->token, $this->position);
    }
}
