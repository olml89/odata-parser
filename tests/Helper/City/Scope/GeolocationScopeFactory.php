<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\Geolocation;

final class GeolocationScopeFactory implements ScopeFactory
{
    public function create(mixed $subject): ?Scope
    {
        return $subject instanceof Geolocation ? new GeolocationScope($subject) : null;
    }
}
