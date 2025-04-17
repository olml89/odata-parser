<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

/**
 * @mixin FunctionNode
 */
trait IsFunction
{
    public function isPrimary(): bool
    {
        return true;
    }
}
