<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression;

use olml89\ODataParser\Parser\Node\Node;

/**
 * @mixin BinaryExpression
 */
trait IsBinaryExpression
{
    use IsExpression;

    public function __construct(
        public readonly Node $left,
        public readonly Node $right,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s %s',
            $this->wrapNode($this->left),
            $this->keyword()->value,
            $this->wrapNode($this->right),
        );
    }
}
