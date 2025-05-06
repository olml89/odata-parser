<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Exception\InvalidTokenException;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Scanner\IdentifierScanner;
use olml89\ODataParser\Lexer\Scanner\KeywordScanner;
use olml89\ODataParser\Lexer\Scanner\NumericScanner;
use olml89\ODataParser\Lexer\Scanner\ScannerPipeline;
use olml89\ODataParser\Lexer\Scanner\SpecialCharScanner;
use olml89\ODataParser\Lexer\Scanner\StringScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScannerPipeline::class)]
#[UsesClass(Char::class)]
#[UsesClass(IdentifierScanner::class)]
#[UsesClass(InvalidTokenException::class)]
#[UsesClass(KeywordScanner::class)]
#[UsesClass(NumericScanner::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
#[UsesClass(SpecialCharScanner::class)]
#[UsesClass(StringScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(ValueToken::class)]
#[UsesTrait(IsNotChar::class)]
final class ScannerPipelineTest extends TestCase
{
    public function testScanReturnsNullIfSourceIsNull(): void
    {
        $scannerPipeline = new ScannerPipeline(null);

        $this->assertNull($scannerPipeline->scan());
    }

    public function testScanReturnsNullIfSourceEof(): void
    {
        $source = new Source('');
        $scannerPipeline = new ScannerPipeline($source);

        $this->assertNull($scannerPipeline->scan());
    }

    public function testScanReturnsNullIfSourceEofAfterConsumingWhitespaces(): void
    {
        $source = new Source('');
        $scannerPipeline = new ScannerPipeline($source);

        $this->assertNull($scannerPipeline->scan());
    }

    public function testScanThrowsInvalidTokenExceptionIfNoScannerInPipelineReturnsToken(): void
    {
        $source = new Source('#');

        $this->expectExceptionObject(
            new InvalidTokenException(new Char('#', position: 0)),
        );

        new ScannerPipeline($source)->scan();
    }

    /**
     * @return array<string, list<string>>
     */
    public static function provideScannable(): array
    {
        return [
            'keyword' => [
                ComparisonOperator::eq->value,
            ],
            'special char' => [
                SpecialChar::OpenParen->value,
            ],
            'identifier' => [
                'abcde',
            ],
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
            'string between single quotes' => [
                '\'abcde\'',
            ],
            'string between double quotes' => [
                '"abcde"',
            ],
            'string with hyphen' => [
                '\'abc-xyz\'',
            ],
        ];
    }

    #[DataProvider('provideScannable')]
    public function testScanReturnsTokenIfScannerInPipelineReturnsToken(string $scannable): void
    {
        $source = new Source($scannable);
        $scannerPipeline = new ScannerPipeline($source);

        $this->assertInstanceOf(Token::class, $scannerPipeline->scan());
    }
}
