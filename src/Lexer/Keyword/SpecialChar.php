<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum SpecialChar: string implements Keyword
{
    case WhiteSpace = ' ';
    case OpenParen = '(';
    case CloseParen = ')';
    case SingleQuote = '\'';
    case DoubleQuote = '"';
    case Comma = ',';
    case Dot = '.';
    case Minus = '-';
    case Colon = ':';
    case Slash = '/';

    public function length(): int
    {
        return 1;
    }
}
