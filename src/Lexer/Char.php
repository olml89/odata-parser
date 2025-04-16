<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use Stringable;

final readonly class Char implements Stringable
{
    public string $char;
    public int $position;

    public function __construct(string $char, int $position)
    {
        $this->char = $char[0];
        $this->position = $position;
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
