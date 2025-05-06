<?php

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;

/**
 * @mixin Nullable
 */
trait IsNullable
{
    /**
     * @throws ValueTypeException
     */
    public static function nullable(mixed $value): NullValue|self
    {
        return is_null($value) ? new NullValue(nullable: true) : self::from($value);
    }
}
