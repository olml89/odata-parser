<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Comparison;

use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\CollectionLambdaOperator;

final readonly class Any extends CollectionLambdaOperator implements Node
{
    protected function keyword(): Keyword
    {
        return CollectionOperator::any;
    }
}
