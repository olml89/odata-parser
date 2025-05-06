<?php

declare(strict_types=1);

namespace Tests\Helper\City;

use Tests\Helper\City\Entity\City;
use Tests\Helper\City\Specification\CitySpecification;

final class CityRepository
{
    /**
     * @var City[]
     */
    public array $cities;

    public function __construct(City ...$cities)
    {
        $cities = count($cities) > 0 ? $cities : [
            CityFactory::beijing(),
            CityFactory::cairo(),
            CityFactory::ciudadDeMexico(),
            CityFactory::delhi(),
            CityFactory::dhaka(),
            CityFactory::mumbai(),
            CityFactory::newYork(),
            CityFactory::shangai(),
            CityFactory::saoPaulo(),
            CityFactory::tokio(),
        ];

        usort(
            $cities,
            fn (City $a, City $b): int => strcmp($a->name->value, $b->name->value),
        );

        $this->cities = $cities;
    }

    /**
     * @return City[]
     */
    public function find(?CitySpecification $specification): array
    {
        if (is_null($specification)) {
            return [];
        }

        return array_values(
            array_filter(
                $this->cities,
                fn (City $city): bool => $specification->isSatisfiedBy($city),
            ),
        );
    }
}
