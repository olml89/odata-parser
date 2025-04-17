<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator;

use olml89\ODataParser\Parser\Node\Node;

abstract readonly class UnaryOperator
{
    use IsOperator;

    public function __construct(
        public Node $operand,
    ) {
    }

    protected function wrapNode(Node $node): string
    {
        return $this->operand instanceof BinaryOperator ? '(' . $node . ')' : (string)$node;
    }
}
