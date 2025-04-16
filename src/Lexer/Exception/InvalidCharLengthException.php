<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

final class InvalidCharLengthException extends LexerException
{
    public function __construct(string $invalidString)
    {
        parent::__construct(
            sprintf(
                'Char must be a single character, string provided: %s',
                $invalidString,
            ),
        );
    }
}
