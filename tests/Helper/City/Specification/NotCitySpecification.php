<?php

declare(strict_types=1);

namespace Tests\Helper\City\Specification;

use Tests\Helper\City\Entity\City;

final readonly class NotCitySpecification
{
    public function __construct(
        private CitySpecification $clause,
    ) {
    }

    public function isSatisfiedBy(City $city): bool
    {
        return !$this->clause->isSatisfiedBy($city);
    }
}
