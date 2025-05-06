<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;

final readonly class FloatValue implements Number, Nullable
{
    use IsValue;
    use IsNumber;
    use IsNullable;

    public static function type(): ValueType
    {
        return ValueType::Float;
    }

    public function __construct(
        private float $value,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public static function from(mixed $value): self
    {
        if (!is_float($value)) {
            throw new ValueTypeException($value, expectedType: self::type());
        }

        return new self($value);
    }

    public function normalize(): Number
    {
        if (round($this->value) === $this->value) {
            return new IntValue((int)$this->value);
        }

        return $this;
    }

    public function round(int $precision = 2): self
    {
        return new self(round($this->value, $precision));
    }

    public function div(Number $number): Number
    {
        return self::number(fdiv($this->value, (float)$number->value()));
    }

    public function mod(Number $number): Number
    {
        return self::number(fmod($this->value, (float)$number->value()));
    }

    public function value(): float
    {
        return $this->value;
    }

    public function float(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
