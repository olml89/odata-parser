<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Scanner\IdentifierScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\ValueToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IdentifierScanner::class)]
#[UsesClass(Char::class)]
#[UsesClass(Source::class)]
#[UsesClass(ValueToken::class)]
final class IdentifierScannerTest extends TestCase
{
    public function testScanReturnsNullIfSourceCannotConsumeAlpha(): void
    {
        $source = new Source('3.1416');
        $scanner = new IdentifierScanner($source);

        $this->assertNull($scanner->scan());
    }

    public function testScanReturnsValueTokenIfSourceCanConsumeAlpha(): void
    {
        $source = new Source('abcdefg');
        $scanner = new IdentifierScanner($source);

        $this->assertInstanceOf(ValueToken::class, $scanner->scan());
    }
}
