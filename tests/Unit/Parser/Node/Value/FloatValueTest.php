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

#[CoversClass(FloatValue::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(ValueTypeException::class)]
final class FloatValueTest extends TestCase
{
    private float $float = 3.1416;

    public function testFrom(): void
    {
        $notFloat = 12;

        $this->expectExceptionObject(
            new ValueTypeException($notFloat, expectedType: ValueType::Float),
        );

        $this->assertEquals(new FloatValue($this->float), FloatValue::from($this->float));
        FloatValue::from($notFloat);
    }

    public function testNullable(): void
    {
        $notFloat = 12;

        $this->expectExceptionObject(
            new ValueTypeException($notFloat, expectedType: ValueType::Float),
        );

        $this->assertEquals(new NullValue(nullable: true), FloatValue::nullable(null));
        $this->assertEquals(new FloatValue($this->float), FloatValue::nullable($this->float));
        FloatValue::nullable($notFloat);
    }

    public function testNormalize(): void
    {
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->normalize(),
        );
        $this->assertEquals(
            new IntValue(3),
            new FloatValue(3.0000)->normalize(),
        );
    }

    public function testRound(): void
    {
        $this->assertEquals(
            new FloatValue(3.1),
            new FloatValue($this->float)->round(1),
        );
        $this->assertEquals(
            new FloatValue(3.14),
            new FloatValue($this->float)->round(2),
        );
        $this->assertEquals(
            new FloatValue(3.142),
            new FloatValue($this->float)->round(3),
        );
        $this->assertEquals(
            new FloatValue(3.1416),
            new FloatValue($this->float)->round(4),
        );
    }

    public function testAdd(): void
    {
        $this->assertEquals(
            new FloatValue(4.1416),
            new FloatValue($this->float)->add(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue(4.1416),
            new FloatValue($this->float)->add(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue(2),
            new FloatValue(1.0)->add(new FloatValue(1.0)),
        );
    }

    public function testSub(): void
    {
        $this->assertEquals(
            new FloatValue(2.1416),
            new FloatValue($this->float)->sub(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue(2.1416),
            new FloatValue($this->float)->sub(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue(1),
            new FloatValue(2.0)->sub(new FloatValue(1.0)),
        );
    }

    public function testMul(): void
    {
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->mul(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->mul(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue(2),
            new FloatValue(2.0)->mul(new FloatValue(1.0)),
        );
    }

    public function testDiv(): void
    {
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->div(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->div(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue(2),
            new FloatValue(2.0)->div(new FloatValue(1.0)),
        );
    }

    public function testDivBy(): void
    {
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->divBy(new FloatValue(1.0)),
        );
        $this->assertEquals(
            new FloatValue($this->float),
            new FloatValue($this->float)->divBy(new IntValue(1)),
        );
        $this->assertEquals(
            new IntValue(2),
            new FloatValue(2.0)->divBy(new FloatValue(1.0)),
        );
    }

    public function testMod(): void
    {
        $this->assertEquals(
            new FloatValue(0.5),
            new FloatValue(2.5)->mod(new FloatValue(2.0)),
        );
        $this->assertEquals(
            new FloatValue(0.5),
            new FloatValue(2.5)->mod(new IntValue(2)),
        );
        $this->assertEquals(
            new IntValue(2),
            new FloatValue(4.0)->divBy(new FloatValue(2.0)),
        );
    }

    public function testMinus(): void
    {
        $this->assertEquals(
            new FloatValue(-$this->float),
            new FloatValue($this->float)->minus(),
        );
    }

    public function testGe(): void
    {
        $this->assertTrue(new FloatValue($this->float)->ge(new FloatValue(3.1))->bool());
        $this->assertTrue(new FloatValue($this->float)->ge(new IntValue(3))->bool());
        $this->assertTrue(new FloatValue($this->float)->ge(new FloatValue($this->float))->bool());
        $this->assertFalse(new FloatValue($this->float)->ge(new FloatValue(3.9))->bool());
        $this->assertFalse(new FloatValue($this->float)->ge(new IntValue(4))->bool());
    }

    public function testGt(): void
    {
        $this->assertTrue(new FloatValue($this->float)->gt(new FloatValue(3.1))->bool());
        $this->assertTrue(new FloatValue($this->float)->gt(new IntValue(3))->bool());
        $this->assertFalse(new FloatValue($this->float)->gt(new FloatValue($this->float))->bool());
        $this->assertFalse(new FloatValue($this->float)->gt(new FloatValue(3.9))->bool());
        $this->assertFalse(new FloatValue($this->float)->gt(new IntValue(4))->bool());
    }

    public function testLe(): void
    {
        $this->assertFalse(new FloatValue($this->float)->le(new FloatValue(3.1))->bool());
        $this->assertFalse(new FloatValue($this->float)->le(new IntValue(3))->bool());
        $this->assertTrue(new FloatValue($this->float)->le(new FloatValue($this->float))->bool());
        $this->assertTrue(new FloatValue($this->float)->le(new FloatValue(3.9))->bool());
        $this->assertTrue(new FloatValue($this->float)->le(new IntValue(4))->bool());
    }

    public function testLt(): void
    {
        $this->assertFalse(new FloatValue($this->float)->lt(new FloatValue(3.1))->bool());
        $this->assertFalse(new FloatValue($this->float)->lt(new IntValue(3))->bool());
        $this->assertFalse(new FloatValue($this->float)->lt(new FloatValue($this->float))->bool());
        $this->assertTrue(new FloatValue($this->float)->lt(new FloatValue(3.9))->bool());
        $this->assertTrue(new FloatValue($this->float)->lt(new IntValue(4))->bool());
    }

    public function testValue(): void
    {
        $this->assertEquals($this->float, new FloatValue($this->float)->value());
    }

    public function testFloat(): void
    {
        $this->assertEquals($this->float, new FloatValue($this->float)->float());
    }

    public function testToString(): void
    {
        $this->assertEquals('3.1416', (string)new FloatValue($this->float));
    }
}
