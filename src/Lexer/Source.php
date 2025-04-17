<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Exception\UnterminatedStringException;
use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;

final class Source
{
    private int $position = 0;

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

    private function substring(int $start, int $length): string
    {
        return mb_substr($this->text, start: $start, length: $length);
    }

    private function charAt(int $position): ?Char
    {
        $char = $this->text[$position] ?? null;

        return is_null($char) ? null : new Char($char, $position);
    }

    /**
     * @throws CharOutOfBoundsException
     */
    public function peek(): Char
    {
        return $this->charAt($this->position) ?? throw new CharOutOfBoundsException($this->position, $this->length());
    }

    private function keywordMatches(Keyword $keyword): bool
    {
        $currentSubstring = $this->substring(start: $this->position, length: mb_strlen($keyword->value));

        if (mb_strtolower($currentSubstring) !== mb_strtolower($keyword->value)) {
            return false;
        }

        /**
         * If it is a special char, we don't check anything else. A match is a match. We only have to check
         * if the keyword can be part of something else with keywords that are not a special char, for ex.,
         * operators, functions...
         */
        if ($keyword instanceof SpecialChar) {
            return true;
        }

        /**
         * If it matches a function name, we have to check that the following character is either an opening
         * parentheses, or that it has an opening parentheses after white spaces:
         *  * contains(name, 'abc')
         *  * contains    (name, 'abc')
         *
         * If not, we have to assume that may be an identifier that's named as a valid function name:
         *  'contains eq true' may refer to a contains boolean property, instead of to the contains function.
         *
         * If there's no char left after the match, we have reached the end of the string without finding a valid
         * OpenParen, so the function call is invalid.
         */
        if ($keyword instanceof FunctionName) {
            $position = $this->position + mb_strlen($keyword->value);

            while (!$this->eof() && $this->charAt($position)?->equals(SpecialChar::WhiteSpace)) {
                ++$position;
            }

            return $this
                ->charAt($position)
                ?->equals(SpecialChar::OpenParen) ?? false;
        }

        /**
         * This comparison is to prevent perfectly valid strings that partially match a keyword,
         * such as 'order', being prematurely lexed into a keyword token and an identifier token for the
         * remaining part, f.ex: 'order' => 'or', 'der'
         *
         * We consider a substring has to be considered ended if it founds a special char, f. ex:
         *  * true,     -> it is a literal true token and a comma token
         *  * trueBlood -> it is a literal string token with value 'trueBlood'
         *
         * In this case, i there's no char left after the match, we have reached the end of the string, so technically
         * we have found a valid keyword, although it is probably syntactically invalid unless it is close
         * parentheses. But that's a problem for the parser.
         */
        return $this
            ->charAt($this->position + mb_strlen($keyword->value))
            ?->equals(...SpecialChar::cases()) ?? true;
    }

    public function find(Keyword ...$keywords): ?Keyword
    {
        $found = array_find(
            $keywords,
            fn (Keyword $keyword): bool => $this->keywordMatches($keyword),
        );

        if (!is_null($found)) {
            $this->advance($found->length());
        }

        return $found;
    }

    /**
     * @throws CharOutOfBoundsException
     */
    public function consumeWhiteSpaces(): void
    {
        while (!$this->eof() && $this->peek()->equals(SpecialChar::WhiteSpace)) {
            $this->advance();
        }
    }

    /**
     * @throws CharOutOfBoundsException
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

        return $this->substring(start: $start, length: $this->position - $start);
    }

    /**
     * @throws CharOutOfBoundsException
     */
    public function consumeNumeric(): ?string
    {
        $char = $this->peek();
        $nextChar = $this->charAt($this->position + 1);

        if (!$char->isDigit() && !($char->equals(SpecialChar::Dot) && $nextChar?->isDigit())) {
            return null;
        }

        $start = $this->position;

        while (!$this->eof() && ($this->peek()->isDigit() || $this->peek()->equals(SpecialChar::Dot))) {
            $this->advance();
        }

        return $this->substring(start: $start, length: $this->position - $start);
    }

    /**
     * @throws CharOutOfBoundsException
     * @throws UnterminatedStringException
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
            throw new UnterminatedStringException();
        }

        $string = $this->substring(start: $start, length: $this->position - $start);

        // Consume closing delimiter
        $this->advance();

        return $string;
    }
}
