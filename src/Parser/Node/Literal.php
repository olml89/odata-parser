<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class Literal implements Node
{
    public NodeType $type {
        get => NodeType::Literal;
    }

    public function __construct(
        public readonly Scalar $value,
    ) {
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitLiteral($this);
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
