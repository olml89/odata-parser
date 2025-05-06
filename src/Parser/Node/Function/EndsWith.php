<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class EndsWith implements FunctionExpression
{
    use IsBinaryFunction;
    use IsStringFunction;

    protected static function name(): FunctionName
    {
        return FunctionName::endswith;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitEndsWith($this);
    }
}
