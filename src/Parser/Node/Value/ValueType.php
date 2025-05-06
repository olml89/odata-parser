<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

enum ValueType: string
{
    case Null = 'null';
    case Bool = 'bool';
    case Int = 'int';
    case Float = 'float';
    case String = 'string';
    case ScalarCollection = 'Collection<null|bool|int|float|string>';
}
