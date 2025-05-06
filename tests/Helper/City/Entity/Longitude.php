<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class Longitude
{
    public function __construct(public float $value)
    {
        assert(
            $this->value >= -180.0 && $this->value <= 180.0,
            'latitude must be between -180º and 180º',
        );
    }
}
