<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Arithmetic;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\BinaryOperator;

/**
 * @mixin ArithmeticNode
 * @mixin BinaryOperator
 */
trait IsArithmetic
{
    public function __toString(): string
    {
        return sprintf(
            '%s %s %s',
            $this->wrapNode($this->left),
            $this->keyword()->value,
            $this->wrapNode($this->right),
        );
    }

    protected function wrapNode(Node $node): string
    {
        return $node instanceof ArithmeticNode && $this->isPreferent()
            ? '(' . $node . ')'
            : (string) $node;
    }
}
