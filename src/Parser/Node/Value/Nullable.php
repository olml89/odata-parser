<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;

interface Nullable extends Scalar
{
    /**
     * @throws ValueTypeException
     */
    public static function from(mixed $value): self;

    /**
     * @throws ValueTypeException
     */
    public static function nullable(mixed $value): NullValue|self;
}
