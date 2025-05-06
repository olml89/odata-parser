<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Comparison;

use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Expression\Expression;
use olml89\ODataParser\Parser\Node\Expression\IsCollectionLambdaExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class All implements Expression
{
    use IsCollectionLambdaExpression;

    protected function keyword(): Keyword
    {
        return CollectionOperator::all;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitAll($this);
    }
}
