<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Arithmetic;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Operator\BinaryOperator;

final readonly class Sub extends BinaryOperator implements ArithmeticNode
{
    use IsArithmetic;
    use HasLowPreference;

    protected function keyword(): Keyword
    {
        return ArithmeticOperator::sub;
    }
}
