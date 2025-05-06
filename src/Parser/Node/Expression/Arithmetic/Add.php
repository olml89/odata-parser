<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Arithmetic;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Expression\BinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class Add implements BinaryExpression
{
    use IsBinaryExpression;

    protected function keyword(): Keyword
    {
        return ArithmeticOperator::add;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitAdd($this);
    }
}
