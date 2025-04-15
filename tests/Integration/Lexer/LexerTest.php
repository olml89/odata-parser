<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Lexer\Scanner\IdentifierScanner;
use olml89\ODataParser\Lexer\Scanner\IsScanner;
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
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Lexer\DataProvider\ArithmeticOperatorProvider;
use Tests\Integration\Lexer\DataProvider\CollectionOperatorProvider;
use Tests\Integration\Lexer\DataProvider\ComparisonOperatorProvider;
use Tests\Integration\Lexer\DataProvider\FunctionProvider;
use Tests\Integration\Lexer\DataProvider\LiteralProvider;
use Tests\Integration\Lexer\DataProvider\LogicalOperatorProvider;
use Tests\Integration\Lexer\DataProvider\SpecialCharProvider;

#[CoversClass(Lexer::class)]
#[UsesClass(Char::class)]
#[UsesClass(LexerException::class)]
#[UsesClass(IdentifierScanner::class)]
#[UsesClass(KeywordScanner::class)]
#[UsesClass(NumericScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(ScannerPipeline::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
#[UsesClass(SpecialCharScanner::class)]
#[UsesClass(StringScanner::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(ValueToken::class)]
#[UsesTrait(IsScanner::class)]
#[UsesTrait(IsNotChar::class)]
final class LexerTest extends TestCase
{
    public function testItTokenizesEmptyStringsAsEmptyArray(): void
    {
        $lexer = new Lexer('');

        $this->assertEmpty($lexer->tokenize());
    }

    public function testItDoesNotTokenizeWhiteSpaces(): void
    {
        $lexer = new Lexer('       ');

        $this->assertEmpty($lexer->tokenize());
    }

    public function testItDoesNotAllowInvalidInputs(): void
    {
        $lexer = new Lexer('#');

        $this->expectExceptionObject(
            LexerException::unknownToken(position: 0),
        );

        $lexer->tokenize();
    }

    /**
     * @return array<string, string[]>
     */
    public static function provideUnterminatedString(): array
    {
        return [
            'unterminated single quote' => [
                '\'abcde',
            ],
            'unterminated double quote' => [
                '"abcde'
            ],
            'single quote with double quote' => [
                '\'abcde"fg',
            ],
            'double quote with single quote' => [
                '"abcde\'fg',
            ],
        ];
    }

    #[DataProvider('provideUnterminatedString')]
    public function testItThrowsLexerExceptionOnUnterminatedStrings(string $unterminatedString): void
    {
        $lexer = new Lexer($unterminatedString);

        $this->expectExceptionObject(
            LexerException::unterminatedString(),
        );

        $lexer->tokenize();
    }

    #[DataProviderExternal(FunctionProvider::class, 'provide')]
    #[DataProviderExternal(SpecialCharProvider::class, 'provide')]
    #[DataProviderExternal(ArithmeticOperatorProvider::class, 'provide')]
    #[DataProviderExternal(ComparisonOperatorProvider::class, 'provide')]
    #[DataProviderExternal(LogicalOperatorProvider::class, 'provide')]
    #[DataProviderExternal(CollectionOperatorProvider::class, 'provide')]
    #[DataProviderExternal(LiteralProvider::class, 'provide')]
    public function testItTokenizesInput(string $input, Token ...$expectedTokens): void
    {
        $lexer = new Lexer($input);

        $this->assertEquals($expectedTokens, $lexer->tokenize());
    }
}
