<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Char::class)]
final class CharTest extends TestCase
{
    public function testItTakesOnlyFirstCharIfStringIsLongerThan1Char(): void
    {
        $string = 'abc';
        $char = new Char($string, position: 0);

        $this->assertEquals('a', (string)$char);
    }

    public function testIsDigit(): void
    {
        $this->assertTrue(new Char('0', position: 0)->isDigit());
        $this->assertFalse(new Char('a', position: 0)->isDigit());
    }

    public function testIsAlpha(): void
    {
        $this->assertFalse(new Char('0', position: 0)->isAlpha());
        $this->assertTrue(new Char('a', position: 0)->isAlpha());
    }

    public function testEquals(): void
    {
        $char = new Char(SpecialChar::Dot->value, position: 0);

        $this->assertTrue($char->equals(SpecialChar::Dot));
        $this->assertFalse($char->equals(SpecialChar::Comma));
    }

    public function testMatches(): void
    {
        $char = new Char(SpecialChar::Dot->value, position: 0);

        $this->assertNotNull($char->matches(SpecialChar::Dot));
        $this->assertNull($char->matches(SpecialChar::Comma));
    }

    public function testToString(): void
    {
        $char = new Char(SpecialChar::OpenParen->value, position: 0);

        $this->assertEquals(SpecialChar::OpenParen->value, (string)$char);
    }
}
