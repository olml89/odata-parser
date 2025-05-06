<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class Neighborhood
{
    public function __construct(
        public Name $name,
        public int $registeredSchoolsCount,
    ) {
    }
}
