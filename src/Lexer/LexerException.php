<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use Exception;

final class LexerException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidCharacterLength(string $invalidString): self
    {
        return new self(
            sprintf(
                'Char must be a single character, string provided: %s',
                $invalidString,
            ),
        );
    }

    public static function unknownToken(int $position): self
    {
        return new self(
            sprintf(
                'Unknown token at position %s',
                $position,
            ),
        );
    }

    public static function unterminatedString(): self
    {
        return new self('Unterminated string');
    }

    public static function outOfBounds(int $position, int $maxPosition): self
    {
        return new self(
            sprintf(
                'Accessing position %s out of %s',
                $position,
                $maxPosition,
            ),
        );
    }
}
