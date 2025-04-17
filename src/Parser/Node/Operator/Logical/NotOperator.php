<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Logical;

use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\UnaryOperator;

final readonly class NotOperator extends UnaryOperator implements Node
{
    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            LogicalOperator::not->value,
            $this->wrapNode($this->operand),
        );
    }
}
