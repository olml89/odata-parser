<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

final class UnterminatedStringException extends LexerException
{
    public function __construct()
    {
        parent::__construct('Unterminated string');
    }
}
