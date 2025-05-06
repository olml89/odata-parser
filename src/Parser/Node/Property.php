<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class Property implements Node
{
    public NodeType $type {
        get => NodeType::Property;
    }

    public function __construct(
        public readonly StringValue $name,
        public readonly ?Property $subProperty = null,
    ) {
    }

    public static function from(string $name, ?Property $subProperty = null): self
    {
        return new self(
            new StringValue($name),
            $subProperty,
        );
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

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitProperty($this);
    }

    public function __toString(): string
    {
        return is_null($this->subProperty)
            ? $this->name->value()
            : sprintf('%s/%s', $this->name->value(), $this->subProperty);
    }
}
