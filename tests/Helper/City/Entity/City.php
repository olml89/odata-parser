<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class City
{
    public function __construct(
        public Name $name,
        public Geolocation $geolocation,
        public Population $population,
        public PoliticalParty $governedBy,
        public bool $isCapital,

        /**
         * @var string[]
         */
        public array $tags,

        /**
         * @var District[]
         */
        public array $districts,
    ) {
    }
}
