<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

interface ScopeFactory
{
    public function create(mixed $subject): ?Scope;
}
