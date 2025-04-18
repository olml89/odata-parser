<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node;

use olml89\ODataParser\Parser\Node\Property;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Property::class)]
final class PropertyTest extends TestCase
{
    /**
     * @return list<list<Property>>
     */
    public static function providePropertyAndSubPropertyAndExpectedProperty(): array
    {
        return [
            [
                new Property('a'),
                new Property('b'),
                new Property(
                    'a',
                    new Property('b'),
                ),
            ],
            [
                new Property(
                    'a',
                    new Property(
                        'b',
                        new Property(
                            'c',
                            new Property('d'),
                        )
                    ),
                ),
                new Property('e'),
                new Property(
                    'a',
                    new Property(
                        'b',
                        new Property(
                            'c',
                            new Property(
                                'd',
                                new Property('e'),
                            ),
                        )
                    ),
                ),
            ],
        ];
    }

    #[DataProvider('providePropertyAndSubPropertyAndExpectedProperty')]
    public function testAddSubProperty(Property $property, Property $subProperty, Property $expectedProperty): void
    {
        $property = $property->addSubProperty($subProperty);

        $this->assertEquals($expectedProperty, $property);
    }
}
