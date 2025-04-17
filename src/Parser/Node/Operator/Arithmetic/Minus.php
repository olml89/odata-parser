<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Arithmetic;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\UnaryOperator;

final readonly class Minus extends UnaryOperator implements Node
{
    public function __toString(): string
    {
        return sprintf(
            '%s%s',
            ArithmeticOperator::minus->value,
            $this->wrapNode($this->operand),
        );
    }
}
