<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Scanner\SpecialCharScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SpecialCharScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
final class SpecialCharScannerTest extends TestCase
{
    public function testScanReturnsNullIfSourceCannotFindASpecialChar(): void
    {
        $source = new Source('3.1416');
        $scanner = new SpecialCharScanner($source);

        $this->assertNull($scanner->scan());
    }

    /**
     * @return array<string, SpecialChar[]>
     */
    public static function provideUnscannableSpecialChar(): array
    {
        return [
            'single quote' => [
                SpecialChar::SingleQuote,
            ],
            'double quote' => [
                SpecialChar::DoubleQuote,
            ],
            'dot' => [
                SpecialChar::Dot,
            ],
        ];
    }

    #[DataProvider('provideUnscannableSpecialChar')]
    public function testScanReturnsNullIfSourceCanFindAnUnscannableSpecialChar(SpecialChar $unscannable): void
    {
        $source = new Source($unscannable->value);
        $scanner = new SpecialCharScanner($source);

        $this->assertNull($scanner->scan());
    }

    /**
     * @return array<string, SpecialChar[]>
     */
    public static function provideScannableSpecialChar(): array
    {
        return [
            'minus' => [
                SpecialChar::Minus,
            ],
            'open parentheses' => [
                SpecialChar::OpenParen,
            ],
            'close parentheses' => [
                SpecialChar::CloseParen,
            ],
            'comma' => [
                SpecialChar::Comma,
            ],
            'colon' => [
                SpecialChar::Colon,
            ],
            'slash' => [
                SpecialChar::Slash,
            ],
        ];
    }

    #[DataProvider('provideScannableSpecialChar')]
    public function testScanReturnsOperatorTokenIfSourceCanFindAScannableSpecialChar(SpecialChar $scannable): void
    {
        $source = new Source($scannable->value);
        $scanner = new SpecialCharScanner($source);

        $this->assertInstanceOf(OperatorToken::class, $scanner->scan());
    }
}
