<?php

declare(strict_types=1);

namespace Tests\Helper\City\Entity;

use Stringable;

final readonly class Name implements Stringable
{
    public function __construct(public string $value)
    {
        assert(
            mb_strlen($this->value) >= 2 && mb_strlen($this->value) <= 100,
            'name must be between 2 and 100 characters',
        );
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
