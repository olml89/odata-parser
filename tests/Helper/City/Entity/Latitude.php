<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class Latitude
{
    public function __construct(public float $value)
    {
        assert(
            $this->value >= -90.0 && $this->value <= 90.0,
            'latitude must be between -90º and 90º',
        );
    }
}
