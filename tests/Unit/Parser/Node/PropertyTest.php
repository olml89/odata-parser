<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node;

use olml89\ODataParser\Parser\Node\Property;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Property::class, 'addSubProperty')]
#[UsesClass(Property::class)]
final class PropertyTest extends TestCase
{
    /**
     * @return list<list<Property>>
     */
    public static function providePropertyAndSubPropertyAndExpectedProperty(): array
    {
        return [
            [
                Property::from('a'),
                Property::from('b'),
                Property::from(
                    'a',
                    Property::from('b'),
                ),
            ],
            [
                Property::from(
                    'a',
                    Property::from(
                        'b',
                        Property::from(
                            'c',
                            Property::from('d'),
                        )
                    ),
                ),
                Property::from('e'),
                Property::from(
                    'a',
                    Property::from(
                        'b',
                        Property::from(
                            'c',
                            Property::from(
                                'd',
                                Property::from('e'),
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
