<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Scanner\KeywordScanner;
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
use Tests\Unit\Lexer\Scanner\DataProvider\KeywordProvider;

#[CoversClass(KeywordScanner::class)]
#[UsesClass(Char::class)]
#[UsesClass(Source::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(ValueToken::class)]
#[UsesTrait(IsNotChar::class)]
final class KeywordScannerTest extends TestCase
{
    /**
     * @return list<list<string>>
     */
    public static function provideNotAKeyword(): array
    {
        return [
            /**
             * Not a keyword
             */
            ['abcde'],
            ['12'],
            ['3.1416'],
            ['\'abcde\''],

            /**
             * Function name not followed by open parentheses
             */
            ['concat'],
            ['contains'],
            ['endswith'],
            ['indexof'],
            ['length'],
            ['matchesPattern'],
            ['startswith'],
            ['substring'],
            ['tolower'],
            ['toupper'],
            ['trim'],
        ];
    }

    #[DataProvider('provideNotAKeyword')]
    public function testScanReturnsNullIfSourceCannotFindAKeyword(string $notAKeyword): void
    {
        $source = new Source($notAKeyword);
        $scanner = new KeywordScanner($source);

        $this->assertNull($scanner->scan());
    }

    #[DataProviderExternal(KeywordProvider::class, 'provide')]
    public function testScanReturnsTokenIfSourceCanFindAKeyword(string $input): void
    {
        $source = new Source($input);
        $scanner = new KeywordScanner($source);

        $this->assertInstanceOf(Token::class, $scanner->scan());
    }
}
