<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\SemanticAnalyzer\Exception\InvalidRegExpException;

final readonly class StringValue implements Scalar, Nullable
{
    use IsValue;
    use IsNullable;

    public static function type(): ValueType
    {
        return ValueType::String;
    }

    public function __construct(
        private string $value,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public static function from(mixed $value): self
    {
        if (!is_string($value)) {
            throw new ValueTypeException($value, expectedType: self::type());
        }

        return new self($value);
    }

    public function concat(StringValue $string): self
    {
        return new self(sprintf('%s%s', $this->value, $string->value));
    }

    public function contains(StringValue $string): BoolValue
    {
        return new BoolValue(str_contains($this->value, $string->value));
    }

    public function endsWith(StringValue $string): BoolValue
    {
        return new BoolValue(str_ends_with($this->value, $string->value));
    }

    public function indexOf(StringValue $string): IntValue
    {
        $indexOf = strpos($this->value, $string->value);

        return new IntValue($indexOf === false ? -1 : $indexOf);
    }

    public function length(): IntValue
    {
        return new IntValue(mb_strlen($this->value));
    }

    /**
     * @throws InvalidRegExpException
     */
    public function matchRegExp(StringValue $string): BoolValue
    {
        $match = preg_match($string->value, $this->value);

        if ($match === false) {
            throw new InvalidRegExpException($string->value);
        }

        return new BoolValue($match === 1);
    }

    public function startsWith(StringValue $string): BoolValue
    {
        return new BoolValue(str_starts_with($this->value, $string->value));
    }

    public function substring(IntValue $start, IntValue|NullValue $length = new NullValue()): StringValue
    {
        return new StringValue(mb_substr($this->value, $start->value(), $length->value()));
    }

    public function toLower(): StringValue
    {
        return new StringValue(mb_strtolower($this->value));
    }

    public function toUpper(): StringValue
    {
        return new StringValue(mb_strtoupper($this->value));
    }

    public function trim(): StringValue
    {
        return new StringValue(mb_trim($this->value));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function string(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return sprintf('\'%s\'', $this->value);
    }
}
