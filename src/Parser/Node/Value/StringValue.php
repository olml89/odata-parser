<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

final readonly class StringValue extends Value
{
    public function __construct(
        public string $value,
    ) {
    }

    public function __toString(): string
    {
        return "'" . $this->value . "'";
    }
}
