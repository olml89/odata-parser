<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

use olml89\ODataParser\Lexer\Char;

final class InvalidTokenException extends LexerException
{
    public function __construct(Char $char)
    {
        parent::__construct(
            sprintf(
                'Unknown token %s at position %s',
                $char,
                $char->position,
            ),
        );
    }
}
