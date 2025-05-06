<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Scanner\NumericScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\ValueToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumericScanner::class)]
#[UsesClass(Char::class)]
#[UsesClass(Source::class)]
#[UsesClass(ValueToken::class)]
final class NumericScannerTest extends TestCase
{
    public function testScanReturnsNullIfSourceCannotConsumeNumeric(): void
    {
        $source = new Source('abcde');
        $scanner = new NumericScanner($source);

        $this->assertNull($scanner->scan());
    }

    /**
     * @return array<string, list<string>>
     */
    public static function provideNumeric(): array
    {
        return [
            'int' => [
                '12',
            ],
            'negative int' => [
                '-12',
            ],
            'float' => [
                '3.1416',
            ],
            'negative float' => [
                '-3.1416',
            ],
        ];
    }

    #[DataProvider('provideNumeric')]
    public function testScanReturnsValueTokenIfSourceCanConsumeNumeric(string $numeric): void
    {
        $source = new Source($numeric);
        $scanner = new NumericScanner($source);

        $this->assertInstanceOf(ValueToken::class, $scanner->scan());
    }
}
