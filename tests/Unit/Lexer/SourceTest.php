<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(Source::class)]
#[UsesClass(Char::class)]
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

    public function testFind(): void
    {
        $findable = ComparisonOperator::eq;
        $source = new Source($findable->value);

        $this->assertNull($source->find(ComparisonOperator::ne));
        $this->assertEquals($findable, $source->find(ComparisonOperator::eq));
    }

    public function testConsumeWhiteSpaces(): void
    {
        $whiteSpaces = '     ';
        $source = new Source($whiteSpaces);

        $source->consumeWhiteSpaces();

        $this->assertEquals(mb_strlen($whiteSpaces), $source->position);
    }

    public function testConsumeAlpha(): void
    {
        $alpha = 'abcde';
        $sourceWithoutAlpha = new Source('3.1416');
        $sourceWithAlpha = new Source($alpha . '3.1416');

        $this->assertNull($sourceWithoutAlpha->consumeAlpha());
        $this->assertEquals($alpha, $sourceWithAlpha->consumeAlpha());
        $this->assertEquals(mb_strlen($alpha), $sourceWithAlpha->position);
    }

    public function testConsumeNumeric(): void
    {
        $integer = '12';
        $float = '3.1416';
        $sourceWithoutNumeric = new Source('abcde');
        $sourceWithInteger = new Source($integer . 'abcde');
        $sourceWithFloat = new Source($float . 'abcde');

        $this->assertNull($sourceWithoutNumeric->consumeNumeric());
        $this->assertEquals($integer, $sourceWithInteger->consumeNumeric());
        $this->assertEquals(mb_strlen($integer), $sourceWithInteger->position);
        $this->assertEquals($float, $sourceWithFloat->consumeNumeric());
        $this->assertEquals(mb_strlen($float), $sourceWithFloat->position);
    }

    public function testConsumeString(): void
    {
        $string = 'abcde';
        $sourceWithoutString = new Source('xyz');
        $sourceWithStringBetweenSingleQuotes = new Source('\'' . $string .'\'' . 'xyz');
        $sourceWithStringBetweenDoubleQuotes = new Source('"' . $string .'"' . 'xyz');

        $this->assertNull($sourceWithoutString->consumeString());
        $this->assertEquals($string, $sourceWithStringBetweenSingleQuotes->consumeString());
        $this->assertEquals(mb_strlen('\''. $string . '\''), $sourceWithStringBetweenSingleQuotes->position);
        $this->assertEquals($string, $sourceWithStringBetweenDoubleQuotes->consumeString());
        $this->assertEquals(mb_strlen('"' . $string . '"'), $sourceWithStringBetweenDoubleQuotes->position);
    }
}
