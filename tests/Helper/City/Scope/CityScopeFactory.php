<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\City;

final class CityScopeFactory implements ScopeFactory
{
    public function create(mixed $subject): ?Scope
    {
        return $subject instanceof City ? new CityScope($subject) : null;
    }
}
