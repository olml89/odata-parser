<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum FunctionName: string implements Keyword
{
    use IsNotChar;

    case concat = 'concat';
    case contains = 'contains';
    case endswith = 'endswith';
    case indexof = 'indexof';
    case length = 'length';
    case matchesPattern = 'matchesPattern';
    case startswith = 'startswith';
    case substring = 'substring';
    case tolower = 'tolower';
    case toupper = 'toupper';
    case trim = 'trim';
}
