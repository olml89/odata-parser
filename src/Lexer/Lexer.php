<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\ValueToken;

final class Lexer
{
    private int $position = 0;

    public function __construct(
        private readonly string $text,
    ) {
    }

    private function length(): int
    {
        return mb_strlen($this->text);
    }

    private function eof(): bool
    {
        return $this->position >= $this->length();
    }

    /**
     * @return Token[]
     *
     * @throws LexerException
     */
    public function tokenize(): array
    {
        $this->position = 0;
        $tokens = [];

        while (!$this->eof()) {
            $this->consumeWhiteSpaces();

            if ($this->eof()) {
                continue;
            }

            $token = $this->consumeKeyword()
                ?? $this->consumeSpecialChar()
                ?? $this->consumeIdentifier()
                ?? $this->consumeNumber()
                ?? $this->consumeString()
                ?? throw LexerException::unknownToken($this->position);

            $tokens[] = $token;
        }

        return $tokens;
    }

    private function advance(int $positions = 1): void
    {
        $this->position += $positions;
    }

    /**
     * @throws LexerException
     */
    private function peek(): Char
    {
        if ($this->eof()) {
            throw LexerException::outOfBounds($this->position, $this->length());
        }

        return new Char($this->text[$this->position], $this->position);
    }

    /**
     * @throws LexerException
     */
    private function next(): ?Char
    {
        $nextPosition = $this->position + 1;
        $char = $this->text[$nextPosition] ?? null;

        return is_null($char) ? null : new Char($char, $nextPosition);
    }

    private function match(Keyword ...$keywords): ?Keyword
    {
        return array_find(
            $keywords,
            function (Keyword $keyword): bool {
                $match = mb_substr($this->text, start: $this->position, length: mb_strlen($keyword->value));

                return mb_strtolower($match) === mb_strtolower($keyword->value);
            },
        );
    }

    /**
     * @throws LexerException
     */
    private function consumeWhiteSpaces(): void
    {
        while (!$this->eof() && $this->peek()->equals(SpecialChar::WhiteSpace)) {
            $this->advance();
        }
    }

    private function consumeKeyword(): ?Token
    {
        /**
         * In OData, the order of precedence for operators from top to bottom is:
         * 1) Parentheses
         * 2) Functions
         * 3) Arithmetical operators (mul, div, mod), (add, sub)
         * 4) Comparison operators
         * 5) Logical operator NOT
         * 6) Logical operator AND
         * 7) Logical operator OR
         */
        $keywords = [
            ...FunctionName::cases(),
            ...ArithmeticOperator::cases(),
            ...ComparisonOperator::cases(),
            ...LogicalOperator::cases(),
            ...CollectionOperator::cases(),
            ...TypeConstant::cases(),
        ];

        $keyword = $this->match(...$keywords);

        $keywordToken = match (true) {
            $keyword instanceof FunctionName => new ValueToken(
                TokenKind::Function,
                $keyword->value,
            ),
            $keyword instanceof ArithmeticOperator => new OperatorToken(
                TokenKind::fromArithmeticOperator($keyword)
            ),
            $keyword instanceof ComparisonOperator => new OperatorToken(
                TokenKind::fromComparisonOperator($keyword),
            ),
            $keyword instanceof LogicalOperator => new OperatorToken(
                TokenKind::fromLogicalOperator($keyword),
            ),
            $keyword instanceof CollectionOperator => new OperatorToken(
                TokenKind::fromCollectionOperator($keyword),
            ),
            $keyword instanceof TypeConstant => new ValueToken(
                TokenKind::fromTypeConstant($keyword),
                $keyword->value,
            ),
            default => null,
        };

        if (!is_null($keyword)) {
            $this->advance($keyword->length());
        }

        return $keywordToken;
    }

    /**
     * @throws LexerException
     */
    private function consumeSpecialChar(): ?OperatorToken
    {
        $specialChar = $this->peek()->matches(
            SpecialChar::OpenParen,
            SpecialChar::CloseParen,
            SpecialChar::Comma,
        );

        $tokenKind = match ($specialChar) {
            SpecialChar::OpenParen => TokenKind::OpenParen,
            SpecialChar::CloseParen => TokenKind::CloseParen,
            SpecialChar::Comma => TokenKind::Comma,
            default => null,
        };

        if (is_null($tokenKind)) {
            return null;
        }

        $this->advance();

        return new OperatorToken($tokenKind);
    }

    /**
     * @throws LexerException
     */
    private function consumeIdentifier(): ?ValueToken
    {
        if (!$this->peek()->isAlpha()) {
            return null;
        }

        $start = $this->position;

        while ($this->position < strlen($this->text) && $this->peek()->isAlpha()) {
            $this->advance();
        }

        $identifier = mb_substr($this->text, start: $start, length: $this->position - $start);

        return new ValueToken(TokenKind::Identifier, $identifier);
    }

    /**
     * @throws LexerException
     */
    private function consumeNumber(): ?ValueToken
    {
        $char = $this->peek();
        $nextChar = $this->next();

        if (!$char->isDigit() && !($char->equals(SpecialChar::Dot) && $nextChar?->isDigit())) {
            return null;
        }

        $start = $this->position;

        while (!$this->eof() && ($this->peek()->isDigit() || $this->peek()->equals(SpecialChar::Dot))) {
            $this->advance();
        }

        $number = mb_substr($this->text, start: $start, length: $this->position - $start);

        return new ValueToken(TokenKind::Number, $number);
    }

    /**
     * @throws LexerException
     */
    private function consumeString(): ?ValueToken
    {
        if (!$this->peek()->equals(SpecialChar::SingleQuote, SpecialChar::DoubleQuote)) {
            return null;
        }

        // Get opening delimiter and consume it
        $delimiter = SpecialChar::from($this->peek()->char);
        $this->advance();
        $start = $this->position;

        while (!$this->eof() && !$this->peek()->equals($delimiter)) {
            $this->advance();
        }

        if ($this->eof()) {
            throw LexerException::unterminatedString();
        }

        $string = mb_substr($this->text, start: $start, length: $this->position - $start);

        // Consume closing delimiter
        $this->advance();

        return new ValueToken(TokenKind::String, $string);
    }
}
