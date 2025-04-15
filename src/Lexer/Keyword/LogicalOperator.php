<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum LogicalOperator: string implements Keyword
{
    use IsNotChar;

    case not = 'not';
    case and = 'and';
    case or = 'or';
}
