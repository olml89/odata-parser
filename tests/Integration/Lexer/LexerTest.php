<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Exception\InvalidTokenException;
use olml89\ODataParser\Lexer\Exception\UnterminatedStringException;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Lexer;
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
use Tests\Integration\Lexer\DataProvider\ArithmeticOperatorTokensProvider;
use Tests\Integration\Lexer\DataProvider\CollectionOperatorTokensProvider;
use Tests\Integration\Lexer\DataProvider\ComparisonOperatorTokensProvider;
use Tests\Integration\Lexer\DataProvider\FunctionTokensProvider;
use Tests\Integration\Lexer\DataProvider\LiteralTokensProvider;
use Tests\Integration\Lexer\DataProvider\LogicalOperatorTokensProvider;
use Tests\Integration\Lexer\DataProvider\PropertyTokensProvider;
use Tests\Integration\Lexer\DataProvider\SpecialCharTokensProvider;

#[CoversClass(Lexer::class)]
#[UsesClass(Char::class)]
#[UsesClass(IdentifierScanner::class)]
#[UsesClass(InvalidTokenException::class)]
#[UsesClass(KeywordScanner::class)]
#[UsesClass(NumericScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(ScannerPipeline::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
#[UsesClass(SpecialCharScanner::class)]
#[UsesClass(StringScanner::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(UnterminatedStringException::class)]
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

    public function testItThrowsInvalidTokenExceptionOnInvalidInputs(): void
    {
        $lexer = new Lexer('#');

        $this->expectExceptionObject(
            new InvalidTokenException(new Char('#', position: 0)),
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
    public function testItThrowsUnterminatedStringExceptionOnUnterminatedStrings(string $unterminatedString): void
    {
        $lexer = new Lexer($unterminatedString);

        $this->expectExceptionObject(
            new UnterminatedStringException($unterminatedString),
        );

        $lexer->tokenize();
    }

    #[DataProviderExternal(FunctionTokensProvider::class, 'provide')]
    #[DataProviderExternal(SpecialCharTokensProvider::class, 'provide')]
    #[DataProviderExternal(ArithmeticOperatorTokensProvider::class, 'provide')]
    #[DataProviderExternal(ComparisonOperatorTokensProvider::class, 'provide')]
    #[DataProviderExternal(LogicalOperatorTokensProvider::class, 'provide')]
    #[DataProviderExternal(CollectionOperatorTokensProvider::class, 'provide')]
    #[DataProviderExternal(PropertyTokensProvider::class, 'provide')]
    #[DataProviderExternal(LiteralTokensProvider::class, 'provide')]
    public function testItTokenizesInput(string $input, Token ...$expectedTokens): void
    {
        $lexer = new Lexer($input);

        $this->assertEquals($expectedTokens, $lexer->tokenize());
    }
}
