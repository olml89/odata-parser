<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

/**
 * @mixin Number
 */
trait IsNumber
{
    private static function number(int|float $value): Number
    {
        return match (true) {
            is_float($value) => new FloatValue($value)->normalize(),
            is_int($value) => new IntValue($value),
        };
    }

    public function add(Number $number): Number
    {
        return self::number($this->value() + $number->value());
    }

    public function sub(Number $number): Number
    {
        return self::number($this->value() - $number->value());
    }

    public function mul(Number $number): Number
    {
        return self::number($this->value() * $number->value());
    }

    public function divBy(Number $number): Number
    {
        return self::number($this->value() / $number->value());
    }

    public function minus(): Number
    {
        return self::number(- $this->value());
    }

    public function ge(Number $number): BoolValue
    {
        return new BoolValue($this->value() >= $number->value());
    }

    public function gt(Number $number): BoolValue
    {
        return new BoolValue($this->value() > $number->value());
    }

    public function le(Number $number): BoolValue
    {
        return new BoolValue($this->value() <= $number->value());
    }

    public function lt(Number $number): BoolValue
    {
        return new BoolValue($this->value() < $number->value());
    }
}
