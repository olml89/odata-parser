<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;

final readonly class IntValue implements Number, Nullable
{
    use IsValue;
    use IsNumber;
    use IsNullable;

    public static function type(): ValueType
    {
        return ValueType::Int;
    }

    public function __construct(
        public int $value,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public static function from(mixed $value): self
    {
        if (!is_int($value)) {
            throw new ValueTypeException($value, expectedType: self::type());
        }

        return new self($value);
    }

    public function normalize(): Number
    {
        return $this;
    }

    public function round(int $precision = 2): Number
    {
        return $this;
    }

    public function div(Number $number): Number
    {
        return $number instanceof IntValue
            ? self::number(intdiv($this->value, $number->int()))
            : self::number(fdiv((float)$this->value, (float)$number->value()));
    }

    public function mod(Number $number): Number
    {
        return $number instanceof IntValue
            ? self::number($this->value % $number->int())
            : self::number(fmod((float)$this->value, (float)$number->value()));
    }

    public function value(): int
    {
        return $this->value;
    }

    public function int(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
