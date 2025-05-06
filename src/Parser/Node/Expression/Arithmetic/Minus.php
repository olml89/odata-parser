<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Arithmetic;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Parser\Node\Expression\UnaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsUnaryExpression;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class Minus implements UnaryExpression
{
    use IsUnaryExpression;

    protected function keyword(): Keyword
    {
        return SpecialChar::Minus;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitMinus($this);
    }
}
