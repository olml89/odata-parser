<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Logical;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Parser\Node\Expression\BinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class OrExpression implements BinaryExpression
{
    use IsBinaryExpression;

    protected function keyword(): Keyword
    {
        return LogicalOperator::or;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitOr($this);
    }
}
