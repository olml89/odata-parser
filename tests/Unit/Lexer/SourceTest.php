<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Lexer\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(Source::class)]
#[UsesClass(Char::class)]
#[UsesClass(CharOutOfBoundsException::class)]
#[UsesClass(SpecialChar::class)]
#[UsesTrait(IsNotChar::class)]
final class SourceTest extends TestCase
{
    public function testLoad(): void
    {
        $this->assertNull(Source::load(null));
        $this->assertInstanceOf(Source::class, Source::load(''));
    }

    public function testEof(): void
    {
        $this->assertTrue(new Source('')->eof());
        $this->assertFalse(new Source(' ')->eof());
    }

    public function testPeek(): void
    {
        $source = new Source('a');

        $this->assertEquals('a', (string) $source->peek());
        $this->assertEquals(0, $source->peek()->position);
    }

    /**
     * @return array<string, array{0: string, 1: Keyword, 2: Keyword}>
     */
    public static function provideInputAndFoundKeyword(): array
    {
        return [
            'keyword followed by whitespace' => [
                'or ',
                LogicalOperator::or,
                LogicalOperator::or,
            ],
            'keyword followed by open parentheses' => [
                'and(',
                LogicalOperator::and,
                LogicalOperator::and,
            ],
            'keyword followed by close parentheses' => [
                'not)',
                LogicalOperator::not,
                LogicalOperator::not,
            ],
            'keyword followed by single quote' => [
                'eq\'',
                ComparisonOperator::eq,
                ComparisonOperator::eq,
            ],
            'keyword followed by double quote' => [
                'ne"',
                ComparisonOperator::ne,
                ComparisonOperator::ne,
            ],
            'keyword followed by comma' => [
                'add,',
                ArithmeticOperator::add,
                ArithmeticOperator::add,
            ],
            'keyword followed by dot' => [
                'sub.',
                ArithmeticOperator::sub,
                ArithmeticOperator::sub,
            ],
            'keyword followed by colon' => [
                'true:',
                TypeConstant::true,
                TypeConstant::true,
            ],
            'keyword followed by slash' => [
                'false/',
                TypeConstant::false,
                TypeConstant::false,
            ],
            'keyword followed by end of string' => [
                'mod',
                ArithmeticOperator::mod,
                ArithmeticOperator::mod,
            ],
            'special char followed by alpha char' => [
                ')a',
                SpecialChar::CloseParen,
                SpecialChar::CloseParen,
            ],
            'special char followed by number' => [
                ')4',
                SpecialChar::CloseParen,
                SpecialChar::CloseParen,
            ],
            'function name followed by open parentheses' => [
                'indexof(',
                FunctionName::indexof,
                FunctionName::indexof,
            ],
            'function name followed by open parentheses after a group of white spaces' => [
                'substring    (',
                FunctionName::substring,
                FunctionName::substring,
            ],
        ];
    }

    /**
     * @return array<string, array{0: string, 1: Keyword, 2: null}>
     */
    public static function provideInputAndNull(): array
    {
        return [
            'function name followed by alpha character after white spaces' => [
                'contains eq true',
                FunctionName::contains,
                null,
            ],
            'function name followed by special char not being open parentheses after white spaces' => [
                'contains  ,',
                FunctionName::contains,
                null,
            ],
            'function name followed by end of string' => [
                'contains',
                FunctionName::contains,
                null,
            ],
            'function name followed by end of string after a group of white spaces' => [
                'substring     ',
                FunctionName::substring,
                null,
            ],
            'keyword followed by any other alpha char' => [
                'nulled',
                TypeConstant::null,
                null,
            ],
            'keyword followed by number' => [
                'any3',
                CollectionOperator::any,
                null,
            ],
        ];
    }

    #[DataProvider('provideInputAndFoundKeyword')]
    #[DataProvider('provideInputAndNull')]
    public function testFind(string $input, Keyword $find, ?Keyword $found): void
    {
        $source = new Source($input);

        $this->assertEquals($found, $source->find($find));
    }

    public function testConsumeWhiteSpaces(): void
    {
        $whiteSpaces = '     ';
        $source = new Source($whiteSpaces);

        $source->consumeWhiteSpaces();

        $this->expectExceptionObject(
            new CharOutOfBoundsException(
                mb_strlen($whiteSpaces),
                mb_strlen($whiteSpaces),
            ),
        );

        $source->peek();
    }

    /**
     * @return array<string, array{0: Source, 1: ?string, 2: ?Char}>
     */
    public static function provideSourceAndExpectedAlphaAndPeek(): array
    {
        return [
            'not alpha' => [
                new Source('3.1416'),
                null,
                null,
            ],
            'alpha' => [
                new Source('abcde3.1416'),
                'abcde',
                new Char('3', 5),
            ],
        ];
    }

    #[DataProvider('provideSourceAndExpectedAlphaAndPeek')]
    public function testConsumeAlpha(Source $source, ?string $expectedAlpha, ?Char $expectedPeek): void
    {
        $this->assertEquals($expectedAlpha, $source->consumeAlpha());

        if (!is_null($expectedPeek)) {
            $this->assertEquals($expectedPeek, $source->peek());
        }
    }

    /**
     * @return array<string, array{0: Source, 1: ?string, 2: ?Char}>
     */
    public static function provideSourceAndExpectedNumericAndPeek(): array
    {
        return [
            'not numeric' => [
                new Source('abcde'),
                null,
                null,
            ],
            'integer' => [
                new Source('12abcde'),
                '12',
                new Char('a', 2),
            ],
            'negative integer' => [
                new Source('-12abcde'),
                '-12',
                new Char('a', 3),
            ],
            'float' => [
                new Source('3.1416abcde'),
                '3.1416',
                new Char('a', 6),
            ],
            'negative float' => [
                new Source('-3.1416abcde'),
                '-3.1416',
                new Char('a', 7),
            ],
        ];
    }

    #[DataProvider('provideSourceAndExpectedNumericAndPeek')]
    public function testConsumeNumeric(Source $source, ?string $expectedNumeric, ?Char $expectedPeek): void
    {
        $this->assertEquals($expectedNumeric, $source->consumeNumeric());

        if (!is_null($expectedPeek)) {
            $this->assertEquals($expectedPeek, $source->peek());
        }
    }

    /**
     * @return array<string, array{0: Source, 1: ?string, 2: ?Char}>
     */
    public static function provideSourceAndExpectedStringAndPeek(): array
    {
        return [
            'no string' => [
                new Source('abcde'),
                null,
                null,
            ],
            'string between single quotes' => [
                new Source('\'xyz\'abcde'),
                'xyz',
                new Char('a', 5),
            ],
            'string between double quotes' => [
                new Source('"xyz"abcde'),
                'xyz',
                new Char('a', 5),
            ],
            'string with hyphen' => [
                new Source('\'vw-xyz\''),
                'vw-xyz',
                null,
            ],
        ];
    }

    #[DataProvider('provideSourceAndExpectedStringAndPeek')]
    public function testConsumeString(Source $source, ?string $expectedString, ?Char $expectedPeek): void
    {
        $this->assertEquals($expectedString, $source->consumeString());

        if (!is_null($expectedPeek)) {
            $this->assertEquals($expectedPeek, $source->peek());
        }
    }
}
