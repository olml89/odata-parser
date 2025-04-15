<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator;

use olml89\ODataParser\Parser\Node\Node;

abstract readonly class UnaryOperator
{
    public function __construct(
        public Node $operand,
    ) {
    }
}
