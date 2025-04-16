<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

final class InvalidTokenException extends LexerException
{
    public function __construct(int $position)
    {
        parent::__construct(
            sprintf(
                'Unknown token at position %s',
                $position,
            ),
        );
    }
}
