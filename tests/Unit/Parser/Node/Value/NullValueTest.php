<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullValue::class)]
#[UsesClass(BoolValue::class)]
final class NullValueTest extends TestCase
{
    public function testNullable(): void
    {
        $this->assertTrue(new NullValue(nullable: true)->nullable());
        $this->assertFalse(new NullValue()->nullable());
    }

    /**
     * @return array<string, Scalar[]>
     */
    public static function provideNotNullScalar(): array
    {
        return [
            'bool value' => [
                new BoolValue(true),
            ],
            'string value' => [
                new StringValue('abcde'),
            ],
            'int value' => [
                new IntValue(12),
            ],
            'float value' => [
                new FloatValue(3.1416),
            ],
        ];
    }

    public function testEqWithNullValues(): void
    {
        $this->assertTrue(new NullValue()->eq(new NullValue())->bool());
    }

    #[DataProvider('provideNotNullScalar')]
    public function testEqWithNotNullScalars(Scalar $scalar): void
    {
        $this->assertFalse(new NullValue()->eq($scalar)->bool());
    }

    public function testNeWithNullValues(): void
    {
        $this->assertFalse(new NullValue()->ne(new NullValue())->bool());
    }

    #[DataProvider('provideNotNullScalar')]
    public function testNeWithNotNullScalars(Scalar $scalar): void
    {
        $this->assertTrue(new NullValue()->ne($scalar)->bool());
    }

    public function testValue(): void
    {
        $this->assertEquals(null, new NullValue()->value());
    }

    public function testNull(): void
    {
        $this->assertEquals(null, new NullValue()->null());
    }

    public function testToString(): void
    {
        $this->assertEquals(ValueType::Null->value, (string)new NullValue());
    }
}
