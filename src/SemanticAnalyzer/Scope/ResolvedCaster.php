<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;

final readonly class ResolvedCaster
{
    /**
     * @throws ValueTypeException
     */
    public function scalar(Resolved $resolved): Scalar
    {
        return $this->tryScalar($resolved) ?? throw new ValueTypeException(
            $resolved,
            ValueType::Null,
            ValueType::Bool,
            ValueType::String,
            ValueType::Int,
            ValueType::Float,
        );
    }

    public function tryScalar(Resolved $resolved): ?Scalar
    {
        return $this->tryNull($resolved)
            ?? $this->tryBool($resolved)
            ?? $this->tryString($resolved)
            ?? $this->tryInt($resolved)
            ?? $this->tryFloat($resolved);
    }

    /**
     * @throws ValueTypeException
     */
    public function null(Resolved $resolved): NullValue
    {
        return $this->tryNull($resolved) ?? throw new ValueTypeException($resolved, expectedTypes: ValueType::Null);
    }

    public function tryNull(Resolved $resolved): ?NullValue
    {
        return $resolved instanceof NullValue ? $resolved : null;
    }

    /**
     * @throws ValueTypeException
     */
    public function bool(Resolved $resolved): BoolValue
    {
        return $this->tryBool($resolved) ?? throw new ValueTypeException($resolved, expectedTypes: ValueType::Bool);
    }

    public function tryBool(Resolved $resolved): ?BoolValue
    {
        return $resolved instanceof BoolValue ? $resolved : null;
    }

    /**
     * @throws ValueTypeException
     */
    public function string(Resolved $resolved): StringValue
    {
        return $this->tryString($resolved) ?? throw new ValueTypeException($resolved, expectedTypes: ValueType::String);
    }

    public function tryString(Resolved $resolved): ?StringValue
    {
        return $resolved instanceof StringValue ? $resolved : null;
    }

    /**
     * @throws ValueTypeException
     */
    public function int(Resolved $resolved): IntValue
    {
        return $this->tryInt($resolved) ?? throw new ValueTypeException($resolved, expectedTypes: ValueType::Int);
    }

    public function tryInt(Resolved $resolved): ?IntValue
    {
        return $resolved instanceof IntValue ? $resolved : null;
    }

    /**
     * @throws ValueTypeException
     */
    public function float(Resolved $resolved): FloatValue
    {
        return $this->tryFloat($resolved) ?? throw new ValueTypeException($resolved, expectedTypes: ValueType::Float);
    }

    public function tryFloat(Resolved $resolved): ?FloatValue
    {
        return $resolved instanceof FloatValue ? $resolved : null;
    }

    /**
     * @throws ValueTypeException
     */
    public function number(Resolved $resolved): Number
    {
        return $this->tryNumber($resolved) ?? throw new ValueTypeException(
            $resolved,
            ValueType::Int,
            ValueType::Float,
        );
    }

    public function tryNumber(Resolved $resolved): ?Number
    {
        return $this->tryInt($resolved) ?? $this->tryFloat($resolved);
    }

    /**
     * @throws ValueTypeException
     */
    public function scopedCollection(Resolved $resolved): ScopedCollection
    {
        return $this->tryScopedCollection($resolved) ?? throw new ValueTypeException(
            $resolved,
            expectedTypes: ResolvedType::ScopedCollection,
        );
    }

    public function tryScopedCollection(Resolved $resolved): ?ScopedCollection
    {
        return $resolved instanceof ScopedCollection ? $resolved : null;
    }
}
