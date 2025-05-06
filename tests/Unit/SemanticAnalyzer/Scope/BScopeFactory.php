<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;

final class BScopeFactory implements ScopeFactory
{
    public function create(mixed $subject): ?Scope
    {
        return $subject instanceof B ? new BScope($subject) : null;
    }
}
