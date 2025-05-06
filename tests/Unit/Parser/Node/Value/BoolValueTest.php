<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BoolValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(ValueTypeException::class)]
final class BoolValueTest extends TestCase
{
    public function testFrom(): void
    {
        $notBool = 'a';

        $this->expectExceptionObject(
            new ValueTypeException($notBool, expectedType: ValueType::Bool),
        );

        $this->assertTrue(BoolValue::from(true)->bool());
        $this->assertFalse(BoolValue::from(false)->bool());
        BoolValue::from($notBool);
    }

    public function testNullable(): void
    {
        $notBool = 'a';

        $this->expectExceptionObject(
            new ValueTypeException($notBool, expectedType: ValueType::Bool),
        );

        $this->assertEquals(new NullValue(nullable: true), BoolValue::nullable(null));
        $this->assertEquals(new BoolValue(true), BoolValue::nullable(true));
        $this->assertEquals(new BoolValue(false), BoolValue::nullable(false));
        BoolValue::nullable($notBool);
    }

    public function testEq(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertTrue($true->eq($true)->bool());
        $this->assertFalse($true->eq($false)->bool());
        $this->assertFalse($false->eq($true)->bool());
        $this->assertTrue($false->eq($false)->bool());
    }

    public function testNe(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertFalse($true->ne($true)->bool());
        $this->assertTrue($true->ne($false)->bool());
        $this->assertTrue($false->ne($true)->bool());
        $this->assertFalse($false->ne($false)->bool());
    }

    public function testAnd(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertTrue($true->and($true)->bool());
        $this->assertFalse($true->and($false)->bool());
        $this->assertFalse($false->and($true)->bool());
        $this->assertFalse($false->and($false)->bool());
    }

    public function testOr(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertTrue($true->or($true)->bool());
        $this->assertTrue($true->or($false)->bool());
        $this->assertTrue($false->or($true)->bool());
        $this->assertFalse($false->or($false)->bool());
    }

    public function testNot(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertFalse($true->not()->bool());
        $this->assertTrue($false->not()->bool());
    }

    public function testValue(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertTrue($true->value());
        $this->assertFalse($false->value());
    }

    public function testBool(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertTrue($true->bool());
        $this->assertFalse($false->bool());
    }

    public function testToString(): void
    {
        $true = new BoolValue(true);
        $false = new BoolValue(false);

        $this->assertEquals(TypeConstant::true->value, (string)$true);
        $this->assertEquals(TypeConstant::false->value, (string)$false);
    }
}
