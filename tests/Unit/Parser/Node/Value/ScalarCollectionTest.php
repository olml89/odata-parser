<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScalarCollection::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(StringValue::class)]
final class ScalarCollectionTest extends TestCase
{
    public function testContains(): void
    {
        $collection = new ScalarCollection(
            new StringValue('a'),
            new StringValue('b'),
            new StringValue('c'),
        );

        $this->assertTrue($collection->contains(new StringValue('b'))->bool());
    }

    public function testValue(): void
    {
        $collection = new ScalarCollection(
            new StringValue('a'),
            new StringValue('b'),
            new StringValue('c'),
        );

        $this->assertEquals(
            $collection->value(),
            [
                new StringValue('a'),
                new StringValue('b'),
                new StringValue('c'),
            ],
        );
    }

    public function testArray(): void
    {
        $collection = new ScalarCollection(
            new StringValue('a'),
            new StringValue('b'),
            new StringValue('c'),
        );

        $this->assertEquals(
            $collection->value(),
            [
                new StringValue('a'),
                new StringValue('b'),
                new StringValue('c'),
            ],
        );
    }

    public function testToString(): void
    {
        $collection = new ScalarCollection(
            new StringValue('a'),
            new StringValue('b'),
            new StringValue('c'),
        );

        $this->assertEquals(
            '[\'a\', \'b\', \'c\']',
            (string)$collection,
        );
    }
}
