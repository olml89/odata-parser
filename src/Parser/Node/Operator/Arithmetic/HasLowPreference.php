<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Arithmetic;

trait HasLowPreference
{
    public function isPreferent(): bool
    {
        return true;
    }
}
