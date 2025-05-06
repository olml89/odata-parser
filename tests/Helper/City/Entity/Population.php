<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

final readonly class Population
{
    public function __construct(public int $value)
    {
        assert($this->value >= 0, 'population should be greater than 0');
    }
}
