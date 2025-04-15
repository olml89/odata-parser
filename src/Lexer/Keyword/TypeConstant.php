<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum TypeConstant: string implements Keyword
{
    use IsNotChar;

    case null = 'null';
    case true = 'true';
    case false = 'false';
}
