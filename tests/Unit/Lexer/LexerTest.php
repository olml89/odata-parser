<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Lexer\LexerException;
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
use Tests\Unit\Lexer\DataProvider\ArithmeticOperatorProvider;
use Tests\Unit\Lexer\DataProvider\CollectionOperatorProvider;
use Tests\Unit\Lexer\DataProvider\ComparisonOperatorProvider;
use Tests\Unit\Lexer\DataProvider\FunctionProvider;
use Tests\Unit\Lexer\DataProvider\LiteralProvider;
use Tests\Unit\Lexer\DataProvider\LogicalOperatorProvider;
use Tests\Unit\Lexer\DataProvider\SpecialCharProvider;

#[CoversClass(Lexer::class)]
#[UsesClass(Char::class)]
#[UsesClass(LexerException::class)]
#[UsesTrait(IsNotChar::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(ValueToken::class)]
final class LexerTest extends TestCase
{
    public function testItDoesNotParseWhiteSpaces(): void
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
