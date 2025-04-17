<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;

final readonly class Substring extends TernaryFunction implements FunctionNode
{
    public static function name(): FunctionName
    {
        return FunctionName::substring;
    }
}
