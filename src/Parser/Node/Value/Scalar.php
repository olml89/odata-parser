<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

interface Scalar extends Value
{
    public function value(): null|bool|int|float|string;
}
