<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

/**
 * @mixin Value
 */
trait IsValue
{
    public function eq(Value $value): BoolValue
    {
        return new BoolValue($this->value() === $value->value());
    }

    public function ne(Value $value): BoolValue
    {
        return new BoolValue($this->value() !== $value->value());
    }
}
