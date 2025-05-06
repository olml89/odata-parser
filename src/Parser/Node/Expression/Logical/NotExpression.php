<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Logical;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Parser\Node\Expression\UnaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsUnaryExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class NotExpression implements UnaryExpression
{
    use IsUnaryExpression;

    protected function keyword(): Keyword
    {
        return LogicalOperator::not;
    }


    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitNot($this);
    }
}
