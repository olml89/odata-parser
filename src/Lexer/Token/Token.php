<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Token;

interface Token extends \Stringable
{
    public TokenKind $kind { get; }
}
