<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Comparison;

use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Expression\BinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class LessThan implements BinaryExpression
{
    use IsBinaryExpression;

    protected function keyword(): Keyword
    {
        return ComparisonOperator::lt;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitLessThan($this);
    }
}
