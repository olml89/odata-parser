<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionNode
 */
abstract readonly class UnaryFunction
{
    public function __construct(
        public Property $property,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s)',
            static::name()->value,
            $this->property,
        );
    }
}
