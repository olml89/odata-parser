<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

final readonly class NullValue implements Scalar
{
    public static function type(): ValueType
    {
        return ValueType::Null;
    }

    public function __construct(
        private bool $nullable = false,
    ) {
    }

    public function nullable(): bool
    {
        return $this->nullable;
    }

    public function eq(Value $value): BoolValue
    {
        return new BoolValue($value instanceof NullValue);
    }

    public function ne(Value $value): BoolValue
    {
        return new BoolValue(!($value instanceof NullValue));
    }

    public function value(): null
    {
        return null;
    }

    public function null(): null
    {
        return null;
    }

    public function __toString(): string
    {
        return self::type()->value;
    }
}
