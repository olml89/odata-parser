<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Scanner\StringScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\ValueToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringScanner::class)]
#[UsesClass(Char::class)]
#[UsesClass(Source::class)]
#[UsesClass(ValueToken::class)]
final class StringScannerTest extends TestCase
{
    public function testScanReturnsNullIfSourceCannotConsumeString(): void
    {
        $source = new Source('null');
        $scanner = new StringScanner($source);

        $this->assertNull($scanner->scan());
    }

    /**
     * @return array<string, list<string>>
     */
    public static function provideStringWithDelimiters(): array
    {
        return [
            'between single quotes' => [
                '\'abcde\'',
            ],
            'between double quotes' => [
                '"abcde"',
            ],
        ];
    }

    #[DataProvider('provideStringWithDelimiters')]
    public function testScanReturnsValueTokenIfSourceCanConsumeNumeric(string $string): void
    {
        $source = new Source($string);
        $scanner = new StringScanner($source);

        $this->assertInstanceOf(ValueToken::class, $scanner->scan());
    }
}
