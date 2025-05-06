<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\Neighborhood;

final class NeighborhoodScopeFactory implements ScopeFactory
{
    public function create(mixed $subject): ?Scope
    {
        return $subject instanceof Neighborhood ? new NeighborhoodScope($subject) : null;
    }
}
