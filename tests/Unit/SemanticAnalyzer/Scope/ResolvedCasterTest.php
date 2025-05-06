<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use olml89\ODataParser\SemanticAnalyzer\Scope\ResolvedCaster;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResolvedCaster::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(FloatValue::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(ScalarCollection::class)]
#[UsesClass(ScopedCollection::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(ValueTypeException::class)]
final class ResolvedCasterTest extends TestCase
{
    private ResolvedCaster $resolvedCaster;

    protected function setUp(): void
    {
        $this->resolvedCaster = new ResolvedCaster();
    }

    /**
     * @return array<string, Scalar[]>
     */
    public static function provideScalar(): array
    {
        return [
            'null value' => [
                new NullValue(),
            ],
            'bool value' => [
                new BoolValue(true),
            ],
            'string value' => [
                new StringValue('a'),
            ],
            'int value' => [
                new IntValue(12),
            ],
            'float value' => [
                new FloatValue(3.1416),
            ],
        ];
    }

    /**
     * @return array<string, Number[]>
     */
    public static function provideNumber(): array
    {
        return [
            'int value' => [
                new IntValue(12),
            ],
            'float value' => [
                new FloatValue(3.1416),
            ],
        ];
    }

    #[DataProvider('provideScalar')]
    public function testScalarCastsScalar(Scalar $scalar): void
    {
        $this->assertEquals($scalar, $this->resolvedCaster->scalar($scalar));
    }

    public function testScalarThrowsValueTypeExceptionIfItCannotCastScalar(): void
    {
        $resolved = new ScalarCollection();

        $this->expectExceptionObject(
            new ValueTypeException(
                $resolved,
                ValueType::Null,
                ValueType::Bool,
                ValueType::String,
                ValueType::Int,
                ValueType::Float,
            ),
        );

        $this->resolvedCaster->scalar($resolved);
    }

    #[DataProvider('provideScalar')]
    public function testTryScalarCastsScalar(Scalar $scalar): void
    {
        $this->assertEquals($scalar, $this->resolvedCaster->tryScalar($scalar));
    }

    public function testTryScalarReturnsNullIfItCannotCastScalar(): void
    {
        $resolved = new ScalarCollection();

        $this->assertNull($this->resolvedCaster->tryScalar($resolved));
    }

    public function testNullCastsNullValue(): void
    {
        $resolved = new NullValue();

        $this->assertEquals($resolved, $this->resolvedCaster->null($resolved));
    }

    public function testNullThrowsValueExceptionIfItCannotCastNullValue(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: NullValue::type()));

        $this->resolvedCaster->null($resolved);
    }

    public function testTryNullCastsNullValue(): void
    {
        $resolved = new NullValue();

        $this->assertEquals($resolved, $this->resolvedCaster->tryNull($resolved));
    }

    public function testTryNullReturnsNullIfItCannotCastNullValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryNull($resolved));
    }

    public function testBoolCastsBoolValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertEquals($resolved, $this->resolvedCaster->bool($resolved));
    }

    public function testBoolThrowsValueExceptionIfItCannotCastBoolValue(): void
    {
        $resolved = new IntValue(12);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: BoolValue::type()));

        $this->resolvedCaster->bool($resolved);
    }

    public function testTryBoolCastsBoolValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertEquals($resolved, $this->resolvedCaster->tryBool($resolved));
    }

    public function testTryBoolReturnsNullIfItCannotCastBoolValue(): void
    {
        $resolved = new IntValue(12);

        $this->assertNull($this->resolvedCaster->tryBool($resolved));
    }

    public function testStringCastsStringValue(): void
    {
        $resolved = new StringValue('a');

        $this->assertEquals($resolved, $this->resolvedCaster->string($resolved));
    }

    public function testStringThrowsValueExceptionIfItCannotCastStringValue(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: StringValue::type()));

        $this->resolvedCaster->string($resolved);
    }

    public function testTryStringCastsStringValue(): void
    {
        $resolved = new StringValue('a');

        $this->assertEquals($resolved, $this->resolvedCaster->tryString($resolved));
    }

    public function testTryStringReturnsNullIfItCannotCastStringValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryString($resolved));
    }

    public function testIntCastsIntValue(): void
    {
        $resolved = new IntValue(12);

        $this->assertEquals($resolved, $this->resolvedCaster->int($resolved));
    }

    public function testIntThrowsValueExceptionIfItCannotCastIntValue(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: IntValue::type()));

        $this->resolvedCaster->int($resolved);
    }

    public function testTryIntCastsIntValue(): void
    {
        $resolved = new IntValue(12);

        $this->assertEquals($resolved, $this->resolvedCaster->tryInt($resolved));
    }

    public function testTryIntReturnsNullIfItCannotCastIntValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryInt($resolved));
    }

    public function testFloatCastsFloatValue(): void
    {
        $resolved = new FloatValue(3.1416);

        $this->assertEquals($resolved, $this->resolvedCaster->float($resolved));
    }

    public function testFloatThrowsValueExceptionIfItCannotCastFloatValue(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: FloatValue::type()));

        $this->resolvedCaster->float($resolved);
    }

    public function testTryFloatCastsFloatValue(): void
    {
        $resolved = new FloatValue(3.1416);

        $this->assertEquals($resolved, $this->resolvedCaster->tryFloat($resolved));
    }

    public function testTryFloatReturnsNullIfItCannotCastFloatValue(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryFloat($resolved));
    }

    #[DataProvider('provideNumber')]
    public function testNumberCastsNumber(Number $number): void
    {
        $this->assertEquals($number, $this->resolvedCaster->number($number));
    }

    public function testNumberThrowsValueExceptionIfItCannotCastNumber(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(
            new ValueTypeException(
                $resolved,
                IntValue::type(),
                FloatValue::type(),
            ),
        );

        $this->resolvedCaster->number($resolved);
    }

    #[DataProvider('provideNumber')]
    public function testTryNumberCastsNumber(Number $number): void
    {
        $this->assertEquals($number, $this->resolvedCaster->tryNumber($number));
    }

    public function testTryNumberReturnsNullIfItCannotCastNumber(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryNumber($resolved));
    }

    public function testScopedCollectionCastsScopedCollection(): void
    {
        $resolved = new ScopedCollection(AScope::factory(), members: []);

        $this->assertEquals($resolved, $this->resolvedCaster->scopedCollection($resolved));
    }

    public function testScopedCollectionThrowsValueExceptionIfItCannotCastScopedCollection(): void
    {
        $resolved = new BoolValue(true);

        $this->expectExceptionObject(new ValueTypeException($resolved, expectedTypes: ScopedCollection::type()));

        $this->resolvedCaster->scopedCollection($resolved);
    }

    public function testTryScopedCollectionCastsScopedCollection(): void
    {
        $resolved = new ScopedCollection(AScope::factory(), members: []);

        $this->assertEquals($resolved, $this->resolvedCaster->tryScopedCollection($resolved));
    }

    public function testTryScopedCollectionReturnsNullIfItCannotCastScopedCollection(): void
    {
        $resolved = new BoolValue(true);

        $this->assertNull($this->resolvedCaster->tryScopedCollection($resolved));
    }
}
