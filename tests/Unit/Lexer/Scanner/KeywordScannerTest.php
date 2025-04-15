<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Lexer\Scanner\KeywordScanner;
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

#[CoversClass(KeywordScanner::class)]
#[UsesClass(Source::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(ValueToken::class)]
#[UsesTrait(IsNotChar::class)]
final class KeywordScannerTest extends TestCase
{
    public function testScanReturnsNullIfSourceCannotFindAKeyword(): void
    {
        $source = new Source('3.1416');
        $scanner = new KeywordScanner($source);

        $this->assertNull($scanner->scan());
    }

    /**
     * @return array<string, Keyword[]>
     */
    public static function provideKeyword(): array
    {
        return [
            'function name' => [
                FunctionName::length,
            ],
            'arithmetic operator' => [
                ArithmeticOperator::add,
            ],
            'comparison operator' => [
                ComparisonOperator::eq,
            ],
            'logical operator' => [
                LogicalOperator::and,
            ],
            'collection operator' => [
                CollectionOperator::any,
            ],
            'type constant' => [
                TypeConstant::null,
            ],
        ];
    }

    #[DataProvider('provideKeyword')]
    public function testScanReturnsTokenIfSourceCanFindAKeyword(Keyword $keyword): void
    {
        $source = new Source($keyword->value);
        $scanner = new KeywordScanner($source);

        $this->assertInstanceOf(Token::class, $scanner->scan());
    }
}
