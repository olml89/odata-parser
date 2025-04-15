<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

final readonly class FloatValue extends Value
{
    public function __construct(
        public float $value,
    ) {
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
