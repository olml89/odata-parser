<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Comparison;

use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\BinaryOperator;

final readonly class Equal extends BinaryOperator implements Node
{
    protected function keyword(): Keyword
    {
        return ComparisonOperator::eq;
    }
}
