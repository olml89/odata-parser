<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum ArithmeticOperator: string implements Keyword
{
    use IsNotChar;

    case mul = 'mul';
    /**
     * Load divBy first as div is a substring of divBy and would be a false positive
     */
    case divBy = 'divby';
    case div = 'div';
    case mod = 'mod';
    case add = 'add';
    case sub = 'sub';
}
