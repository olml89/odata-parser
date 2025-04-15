<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

final readonly class IntValue extends Value
{
    public function __construct(
        public int $value,
    ) {
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
