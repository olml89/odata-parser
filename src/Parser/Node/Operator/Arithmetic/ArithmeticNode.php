<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Arithmetic;

use olml89\ODataParser\Parser\Node\Node;

interface ArithmeticNode extends Node
{
    public function isPreferent(): bool;
}
