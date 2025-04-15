<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;

final class Source
{
    private(set) public int $position = 0;

    public function __construct(
        private readonly string $text,
    ) {
    }

    public static function load(?string $text): ?self
    {
        return is_null($text) ? null : new self($text);
    }

    private function length(): int
    {
        return mb_strlen($this->text);
    }

    public function eof(): bool
    {
        return $this->position >= $this->length();
    }

    private function advance(int $positions = 1): void
    {
        $this->position += $positions;
    }

    private function substring(int $start): string
    {
        return mb_substr($this->text, start: $start, length: $this->position - $start);
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

    public function find(Keyword ...$keywords): ?Keyword
    {
        $found = array_find(
            $keywords,
            function (Keyword $keyword): bool {
                $match = mb_substr($this->text, start: $this->position, length: mb_strlen($keyword->value));

                return mb_strtolower($match) === mb_strtolower($keyword->value);
            },
        );

        if (!is_null($found)) {
            $this->advance($found->length());
        }

        return $found;
    }

    /**
     * @throws LexerException
     */
    public function consumeWhiteSpaces(): void
    {
        while (!$this->eof() && $this->peek()->equals(SpecialChar::WhiteSpace)) {
            $this->advance();
        }
    }

    /**
     * @throws LexerException
     */
    public function consumeAlpha(): ?string
    {
        if (!$this->peek()->isAlpha()) {
            return null;
        }

        $start = $this->position;

        while (!$this->eof() && $this->peek()->isAlpha()) {
            $this->advance();
        }

        return $this->substring($start);
    }

    /**
     * @throws LexerException
     */
    public function consumeNumeric(): ?string
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

        return $this->substring($start);
    }

    /**
     * @throws LexerException
     */
    public function consumeString(): ?string
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

        $string = $this->substring($start);

        // Consume closing delimiter
        $this->advance();

        return $string;
    }
}
