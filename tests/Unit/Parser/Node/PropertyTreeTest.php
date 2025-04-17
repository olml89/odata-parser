<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\PropertyTree;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PropertyTree::class)]
#[UsesClass(Property::class)]
final class PropertyTreeTest extends TestCase
{
    public function testBuildReturnsParentPropertyIfThereAreNoSubProperties(): void
    {
        $a = new Property('a');

        $propertyTree = new PropertyTree($a);

        $this->assertEquals($a, $propertyTree->build());
    }

    public function testBuildReturnsProperPropertyTreeIfThereAreSubProperties(): void
    {
        $a = new Property('a');
        $b = new Property('b');
        $c = new Property('c');

        $propertyTree = new PropertyTree($a);
        $propertyTree->addSubProperty($b);
        $propertyTree->addSubProperty($c);

        $this->assertEquals(
            new Property(
                'a',
                new Property(
                    'b',
                    new Property('c'),
                ),
            ),
            $propertyTree->build(),
        );
    }
}
