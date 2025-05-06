<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

enum NodeType: string
{
    case Literal = 'literal';
    case Property = 'property';
    case Function = 'function';
    case Expression = 'expression';
}
