<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Parser\Exception\ValueTypeException;

final readonly class BoolValue implements Nullable
{
    use IsValue;
    use IsNullable;

    public static function type(): ValueType
    {
        return ValueType::Bool;
    }

    public function __construct(
        private bool $value,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public static function from(mixed $value): self
    {
        if (!is_bool($value)) {
            throw new ValueTypeException($value, expectedType: self::type());
        }

        return new self($value);
    }

    public function and(BoolValue $bool): BoolValue
    {
        return new BoolValue($this->value && $bool->value);
    }

    public function or(BoolValue $bool): BoolValue
    {
        return new BoolValue($this->value || $bool->value);
    }

    public function not(): BoolValue
    {
        return new BoolValue(!$this->value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function bool(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return ($this->value) ? TypeConstant::true->value : TypeConstant::false->value ;
    }
}
