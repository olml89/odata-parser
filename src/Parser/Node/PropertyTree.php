<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

final class PropertyTree
{
    /**
     * @var Property[]
     */
    private array $subProperties = [];

    public function __construct(
        private readonly Property $parent,
    ) {
    }

    public function addSubProperty(Property $property): void
    {
        $this->subProperties[] = $property;
    }

    public function build(): Property
    {
        $subProperties = array_reduce(
            array_reverse($this->subProperties),
            fn (?Property $carry, Property $item): Property => new Property($item->name, $carry),
        );

        return new Property($this->parent->name, $subProperties);
    }
}
