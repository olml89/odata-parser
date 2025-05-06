<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression;

use olml89\ODataParser\Parser\Node\Node;

/**
 * @mixin UnaryExpression
 */
trait IsUnaryExpression
{
    use IsExpression;

    public function __construct(
        public readonly Node $operand,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            $this->keyword()->value,
            $this->wrapNode($this->operand),
        );
    }
}
