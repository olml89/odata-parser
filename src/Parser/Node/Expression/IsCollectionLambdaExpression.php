<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin Expression
 */
trait IsCollectionLambdaExpression
{
    use IsExpression;

    public function __construct(
        public readonly Property $property,
        public readonly Property $variable,
        public readonly Node $predicate,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s/%s(%s: %s)',
            $this->property,
            $this->keyword()->value,
            $this->variable,
            $this->predicate,
        );
    }
}
