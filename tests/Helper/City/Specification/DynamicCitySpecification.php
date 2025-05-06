<?php

declare(strict_types=1);

namespace Tests\Helper\City\Specification;

use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;
use Tests\Helper\City\Entity\City;

final readonly class DynamicCitySpecification implements CitySpecification
{
    public function __construct(
        private DeferredPredicate $predicate,
    ) {
    }

    public function isSatisfiedBy(City $city): bool
    {
        return $this->predicate->fetch($city)->value();
    }
}
