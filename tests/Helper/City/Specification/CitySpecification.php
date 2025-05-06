<?php

declare(strict_types=1);

namespace Tests\Helper\City\Specification;

use Tests\Helper\City\Entity\City;

interface CitySpecification
{
    public function isSatisfiedBy(City $city): bool;
}
