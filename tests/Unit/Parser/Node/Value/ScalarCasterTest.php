<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\Value;

use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Exception\CastingException;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\Parser\Node\Value\ScalarCaster;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScalarCaster::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(CastingException::class)]
#[UsesClass(FloatValue::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(ValueToken::class)]
final class ScalarCasterTest extends TestCase
{
    private ScalarCaster $scalarCaster;

    protected function setUp(): void
    {
        $this->scalarCaster = new ScalarCaster();
    }

    /**
     * @return array<string, array{0: ValueToken, 1: CastingException}>
     */
    public static function provideInvalidValueTokenAndExpectedException(): array
    {
        return [
            'invalid token kind' => [
                $invalidValueToken = new ValueToken(TokenKind::And, 'abcde'),
                CastingException::invalidTokenKind($invalidValueToken->kind),
            ],
            'invalid boolean' => [
                new ValueToken(TokenKind::Boolean, $invalidBoolean = 'abcde'),
                CastingException::fromBool($invalidBoolean),
            ],
            'invalid number' => [
                new ValueToken(TokenKind::Number, $invalidNumber = 'abcde'),
                CastingException::fromNumber($invalidNumber),
            ],
        ];
    }

    #[DataProvider('provideInvalidValueTokenAndExpectedException')]
    public function testItThrowsCastingException(ValueToken $invalidValueToken, CastingException $exception): void
    {
        $this->expectExceptionObject($exception);

        $this->scalarCaster->cast($invalidValueToken);
    }

    public function testItCastsToNullValue(): void
    {
        $token = new ValueToken(TokenKind::Null, 'null');

        $cast = $this->scalarCaster->cast($token);

        // $cast->value() will always be null for NullValue, no need to test that
        $this->assertInstanceOf(NullValue::class, $cast);
    }

    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideBooleanInputAndExpectedBool(): array
    {
        return [
            'true' => [
                'true',
                true,
            ],
            'false' => [
                'false',
                false,
            ],
        ];
    }

    #[DataProvider('provideBooleanInputAndExpectedBool')]
    public function testItCastsToBooleanValue(string $booleanInput, bool $expectedBool): void
    {
        $token = new ValueToken(TokenKind::Boolean, $booleanInput);

        $cast = $this->scalarCaster->cast($token);

        $this->assertInstanceOf(BoolValue::class, $cast);
        $this->assertEquals($expectedBool, $cast->value());
    }

    /**
     * @return array<string, array{0: string, 1: int|float}>
     */
    public static function provideNumericInputAndExpectedNumber(): array
    {
        return [
            'float' => [
                '3.1416',
                3.1416,
            ],
            'float normalizable to int' => [
                '3.00',
                3,
            ],
            'int' => [
                '12',
                12,
            ],
        ];
    }

    #[DataProvider('provideNumericInputAndExpectedNumber')]
    public function testItCastsToNumber(string $numericInput, int|float $expectedNumber): void
    {
        $token = new ValueToken(TokenKind::Number, $numericInput);

        $cast = $this->scalarCaster->cast($token);

        $this->assertInstanceOf(Number::class, $cast);
        $this->assertEquals($expectedNumber, $cast->value());
    }

    public function testItCastsToStringValue(): void
    {
        $token = new ValueToken(TokenKind::String, $string = 'abcde');

        $cast = $this->scalarCaster->cast($token);

        $this->assertInstanceOf(StringValue::class, $cast);
        $this->assertEquals($string, $cast->value());
    }
}
