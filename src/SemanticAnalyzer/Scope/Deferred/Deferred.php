<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;

interface Deferred
{
    public function fetch(mixed $subject): Resolved;
}
