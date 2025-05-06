<?php

declare(strict_types=1);

namespace Tests\Helper\City\Specification;

use Tests\Helper\City\Entity\City;

final readonly class OrCitySpecification
{
    /**
     * @var CitySpecification[]
     */
    private array $clauses;

    public function __construct(CitySpecification ...$clauses)
    {
        $this->clauses = $clauses;
    }

    public function isSatisfiedBy(City $city): bool
    {
        return array_all(
            $this->clauses,
            fn (CitySpecification $clause): bool => $clause->isSatisfiedBy($city),
        );
    }
}
