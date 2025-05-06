<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class Geolocation
{
    public function __construct(
        public Latitude $latitude,
        public Longitude $longitude,
    ) {
    }
}
