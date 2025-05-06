<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IntValue::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(FloatValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(ValueTypeException::class)]
final class IntValueTest extends TestCase
{
    private int $int = 12;

    public function testFrom(): void
    {
        $notInt = 3.1416;

        $this->expectExceptionObject(
            new ValueTypeException($notInt, expectedType: ValueType::Int),
        );

        $this->assertEquals(new IntValue($this->int), IntValue::from($this->int));
        IntValue::from($notInt);
    }

    public function testNullable(): void
    {
        $notInt = 3.1416;

        $this->expectExceptionObject(
            new ValueTypeException($notInt, expectedType: ValueType::Int),
        );

        $this->assertEquals(new NullValue(nullable: true), IntValue::nullable(null));
        $this->assertEquals(new IntValue($this->int), IntValue::nullable($this->int));
        IntValue::nullable($notInt);
    }

    public function testNormalize(): void
    {
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->normalize(),
        );
    }

    public function testRound(): void
    {
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->round(),
        );
    }

    public function testAdd(): void
    {
        $this->assertEquals(
            new IntValue(15),
            new IntValue($this->int)->add(new IntValue(3)),
        );
        $this->assertEquals(
            new IntValue(15),
            new IntValue($this->int)->add(new FloatValue(3.0)),
        );
        $this->assertEquals(
            new FloatValue(15.5),
            new IntValue($this->int)->add(new FloatValue(3.5)),
        );
    }

    public function testSub(): void
    {
        $this->assertEquals(
            new IntValue(10),
            new IntValue($this->int)->sub(new IntValue(2)),
        );
        $this->assertEquals(
            new IntValue(10),
            new IntValue($this->int)->sub(new FloatValue(2.0)),
        );
        $this->assertEquals(
            new FloatValue(9.5),
            new IntValue($this->int)->sub(new FloatValue(2.5)),
        );
    }

    public function testMul(): void
    {
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->mul(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->mul(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue(19.80),
            new IntValue($this->int)->mul(new FloatValue(1.65))->round(),
        );
    }

    public function testDiv(): void
    {
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->div(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->div(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue(10.43),
            new IntValue($this->int)->div(new FloatValue(1.15))->round(),
        );
    }

    public function testDivBy(): void
    {
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->divBy(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue($this->int),
            new IntValue($this->int)->divBy(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue(10.43),
            new IntValue($this->int)->divBy(new FloatValue(1.15))->round(),
        );
    }

    public function testMod(): void
    {
        $this->assertEquals(
            new IntValue(2),
            new IntValue($this->int)->mod(new IntValue(5)),
        );
        $this->assertEquals(
            new IntValue(0),
            new IntValue($this->int)->mod(new FloatValue(2.0)),
        );
        $this->assertEquals(
            new FloatValue(1.5),
            new IntValue($this->int)->mod(new FloatValue(1.75))->round(),
        );
    }

    public function testMinus(): void
    {
        $this->assertEquals(
            new IntValue(-$this->int),
            new IntValue($this->int)->minus(),
        );
    }

    public function testGe(): void
    {
        $this->assertTrue(new IntValue($this->int)->ge(new IntValue(3))->bool());
        $this->assertTrue(new IntValue($this->int)->ge(new FloatValue(11.5))->bool());
        $this->assertTrue(new IntValue($this->int)->ge(new IntValue($this->int))->bool());
        $this->assertFalse(new IntValue($this->int)->ge(new IntValue(15))->bool());
        $this->assertFalse(new IntValue($this->int)->ge(new FloatValue(12.5))->bool());
    }

    public function testGt(): void
    {
        $this->assertTrue(new IntValue($this->int)->gt(new IntValue(3))->bool());
        $this->assertTrue(new IntValue($this->int)->gt(new FloatValue(11.5))->bool());
        $this->assertFalse(new IntValue($this->int)->gt(new IntValue($this->int))->bool());
        $this->assertFalse(new IntValue($this->int)->gt(new IntValue(15))->bool());
        $this->assertFalse(new IntValue($this->int)->gt(new FloatValue(12.5))->bool());
    }

    public function testLe(): void
    {
        $this->assertFalse(new IntValue($this->int)->le(new IntValue(3))->bool());
        $this->assertFalse(new IntValue($this->int)->le(new FloatValue(11.5))->bool());
        $this->assertTrue(new IntValue($this->int)->le(new IntValue($this->int))->bool());
        $this->assertTrue(new IntValue($this->int)->le(new IntValue(15))->bool());
        $this->assertTrue(new IntValue($this->int)->le(new FloatValue(12.5))->bool());
    }

    public function testLt(): void
    {
        $this->assertFalse(new IntValue($this->int)->lt(new IntValue(3))->bool());
        $this->assertFalse(new IntValue($this->int)->lt(new FloatValue(11.5))->bool());
        $this->assertFalse(new IntValue($this->int)->lt(new IntValue($this->int))->bool());
        $this->assertTrue(new IntValue($this->int)->lt(new IntValue(15))->bool());
        $this->assertTrue(new IntValue($this->int)->lt(new FloatValue(12.5))->bool());
    }

    public function testValue(): void
    {
        $this->assertEquals($this->int, new IntValue($this->int)->value());
    }

    public function testFloat(): void
    {
        $this->assertEquals($this->int, new IntValue($this->int)->int());
    }

    public function testToString(): void
    {
        $this->assertEquals('12', (string)new IntValue($this->int));
    }
}
