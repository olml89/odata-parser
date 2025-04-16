<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Exception\InvalidCharLengthException;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use Stringable;

final readonly class Char implements Stringable
{
    /**
     * @throws InvalidCharLengthException
     */
    public function __construct(
        public string $char,
        public int $position,
    ) {
        if (mb_strlen($this->char) > 1) {
            throw new InvalidCharLengthException($this->char);
        }
    }

    public function isDigit(): bool
    {
        return ctype_digit($this->char);
    }

    public function isAlpha(): bool
    {
        return ctype_alpha($this->char);
    }

    public function equals(SpecialChar ...$specialChars): bool
    {
        return array_any(
            $specialChars,
            fn (SpecialChar $specialChar): bool => $this->char === $specialChar->value,
        );
    }

    public function matches(SpecialChar ...$specialChars): ?SpecialChar
    {
        return array_find(
            $specialChars,
            fn (SpecialChar $specialChar): bool => $this->char === $specialChar->value,
        );
    }

    public function __toString(): string
    {
        return $this->char;
    }
}
