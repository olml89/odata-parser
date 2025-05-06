<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\DataProvider;

use olml89\ODataParser\Parser\Node\Expression\BinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\Expression;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\NodeType;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

abstract readonly class NodeProvider
{
    protected static function property(): Property
    {
        return Property::from('property');
    }

    protected static function literal(): Literal
    {
        return new Literal(new StringValue('literal'));
    }

    protected static function binary(): BinaryExpression
    {
        return new class () implements BinaryExpression {
            public NodeType $type {
                get => NodeType::Expression;
            }

            public function accept(Visitor $visitor): null
            {
                return null;
            }

            public function __toString(): string
            {
                return 'binary';
            }
        };
    }

    protected static function expression(): Expression
    {
        return new class () implements Expression {
            public NodeType $type {
                get => NodeType::Expression;
            }

            public function accept(Visitor $visitor): null
            {
                return null;
            }

            public function __toString(): string
            {
                return 'expression';
            }
        };
    }
}
