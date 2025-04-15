<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum ComparisonOperator: string implements Keyword
{
    use IsNotChar;

    case eq = 'eq';
    case ne = 'ne';
    case gt = 'gt';
    case ge = 'ge';
    case lt = 'lt';
    case le = 'le';
    case in = 'in';
    case has = 'has';
}
