<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class ToUpper implements FunctionExpression
{
    use IsUnaryFunction;

    protected static function name(): FunctionName
    {
        return FunctionName::toupper;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitToUpper($this);
    }
}
