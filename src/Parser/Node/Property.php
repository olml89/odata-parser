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

    public function isPrimary(): bool
    {
        return true;
    }

    public function addSubProperty(Property $property): self
    {
        if (is_null($this->subProperty)) {
            return new Property(
                name: $this->name,
                subProperty: $property,
            );
        }

        return new Property(
            name: $this->name,
            subProperty: $this->subProperty->addSubProperty($property),
        );
    }

    public function __toString(): string
    {
        return is_null($this->subProperty)
            ? $this->name
            : sprintf('%s/%s', $this->name, $this->subProperty);
    }
}
