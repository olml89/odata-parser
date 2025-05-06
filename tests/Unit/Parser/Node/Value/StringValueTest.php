<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringValue::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(ValueTypeException::class)]
final class StringValueTest extends TestCase
{
    private string $string = 'abcde';

    public function testFrom(): void
    {
        $notString = 12;

        $this->expectExceptionObject(
            new ValueTypeException($notString, expectedType: ValueType::String),
        );

        $this->assertEquals(new StringValue($this->string), StringValue::from($this->string));
        StringValue::from($notString);
    }

    public function testNullable(): void
    {
        $notString = 12;

        $this->expectExceptionObject(
            new ValueTypeException($notString, expectedType: ValueType::String),
        );

        $this->assertEquals(new NullValue(nullable: true), StringValue::nullable(null));
        $this->assertEquals(new StringValue($this->string), StringValue::nullable($this->string));
        StringValue::nullable($notString);
    }

    public function testEq(): void
    {
        $this->assertTrue(new StringValue($this->string)->eq(new StringValue($this->string))->bool());
        $this->assertFalse(new StringValue($this->string)->eq(new StringValue('b'))->bool());
    }

    public function testNe(): void
    {
        $this->assertFalse(new StringValue($this->string)->ne(new StringValue($this->string))->bool());
        $this->assertTrue(new StringValue($this->string)->ne(new StringValue('b'))->bool());
    }

    public function testConcat(): void
    {
        $this->assertEquals(
            new StringValue($this->string),
            new StringValue('abc')->concat(new StringValue('de')),
        );
    }

    public function testContains(): void
    {
        $this->assertTrue(new StringValue($this->string)->contains(new StringValue('a'))->bool());
        $this->assertFalse(new StringValue($this->string)->contains(new StringValue('x'))->bool());
    }

    public function testEndsWith(): void
    {
        $this->assertTrue(new StringValue($this->string)->endsWith(new StringValue('de'))->bool());
        $this->assertFalse(new StringValue($this->string)->endsWith(new StringValue('cd'))->bool());
    }

    public function testIndexOf(): void
    {
        $this->assertEquals(
            new IntValue(0),
            new StringValue($this->string)->indexOf(new StringValue('a')),
        );
        $this->assertEquals(
            new IntValue(4),
            new StringValue($this->string)->indexOf(new StringValue('e')),
        );
    }

    public function testLength(): void
    {
        $this->assertEquals(
            new IntValue(mb_strlen($this->string)),
            new StringValue($this->string)->length(),
        );
    }

    public function testMatchRegExp(): void
    {
        $regExp = new StringValue('\'/x/i\'');

        $this->assertFalse(new StringValue($this->string)->matchRegExp($regExp)->bool());
        $this->assertFalse(new StringValue('xyz')->matchRegExp($regExp)->bool());
    }

    public function testStartsWith(): void
    {
        $this->assertTrue(new StringValue($this->string)->startsWith(new StringValue('ab'))->bool());
        $this->assertFalse(new StringValue($this->string)->endsWith(new StringValue('bc'))->bool());
    }

    public function testSubstring(): void
    {
        $this->assertEquals(
            new StringValue('ab'),
            new StringValue($this->string)->substring(start: new IntValue(0), length: new IntValue(2)),
        );
        $this->assertEquals(
            new StringValue($this->string),
            new StringValue($this->string)->substring(start: new IntValue(0)),
        );
    }

    public function testToLower(): void
    {
        $this->assertEquals(
            new StringValue($this->string),
            new StringValue('aBcDe')->toLower(),
        );
    }

    public function testToUpper(): void
    {
        $this->assertEquals(
            new StringValue(mb_strtoupper($this->string)),
            new StringValue('aBcDe')->toUpper(),
        );
    }

    public function testTrim(): void
    {
        $this->assertEquals(
            new StringValue($this->string),
            new StringValue('  abcde  ')->trim(),
        );
    }

    public function testValue(): void
    {
        $this->assertEquals($this->string, new StringValue($this->string)->value());
    }

    public function testString(): void
    {
        $this->assertEquals($this->string, new StringValue($this->string)->string());
    }

    public function testToString(): void
    {
        $this->assertEquals(sprintf('\'%s\'', $this->string), (string)new StringValue($this->string));
    }
}
