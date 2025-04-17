<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator;

use olml89\ODataParser\Parser\Node\Node;

/**
 * @mixin Node
 */
trait IsOperator
{
    public function isPrimary(): bool
    {
        return false;
    }
}
