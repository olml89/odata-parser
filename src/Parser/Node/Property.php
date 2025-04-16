<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

final readonly class Property implements Node
{
    public function __construct(
        public string $name,
        public ?Property $subProperty = null,
    ) {
    }

    public function __toString(): string
    {
        return is_null($this->subProperty)
            ? $this->name
            : sprintf('%s/%s', $this->name, $this->subProperty);
    }
}
