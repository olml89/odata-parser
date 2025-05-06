<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

final class UnterminatedStringException extends LexerException
{
    public function __construct(string $unterminatedString)
    {
        parent::__construct(
            sprintf(
                'Unterminated string: %s',
                $unterminatedString,
            ),
        );
    }
}
