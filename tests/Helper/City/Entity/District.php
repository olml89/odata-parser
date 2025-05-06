<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class District
{
    public function __construct(
        public Name $name,

        /**
         * @var Neighborhood[]
         */
        public array $neighborhoods,
    ) {
    }
}
