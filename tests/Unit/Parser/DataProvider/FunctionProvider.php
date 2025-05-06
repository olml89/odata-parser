<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\ToUpper;
use olml89\ODataParser\Parser\Node\Function\Trim;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class FunctionProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'concat' => [
                new Concat(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'concat',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'contains' => [
                new Contains(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'contains',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'endswith' => [
                new EndsWith(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'endswith',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'indexof' => [
                new IndexOf(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'indexof',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'length' => [
                new Length(
                    Property::from('identifier'),
                ),
                ...self::createUnaryFunctionTokens(
                    functionName: 'length',
                    identifier: 'identifier',
                ),
            ],
            'matchesPattern' => [
                new MatchesPattern(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'matchesPattern',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'startswith' => [
                new StartsWith(
                    Property::from('identifier'),
                    new Literal(new StringValue('abcde')),
                ),
                ...self::createBinaryFunctionTokens(
                    functionName: 'startswith',
                    identifier: 'identifier',
                    value: 'abcde',
                ),
            ],
            'substring' => [
                new Substring(
                    Property::from('identifier'),
                    new Literal(new IntValue(2)),
                    new Literal(new IntValue(4)),
                ),
                new ValueToken(
                    TokenKind::Function,
                    'substring',
                ),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(
                    TokenKind::Number,
                    '2',
                ),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(
                    TokenKind::Number,
                    '4',
                ),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'tolower' => [
                new ToLower(
                    Property::from('identifier'),
                ),
                ...self::createUnaryFunctionTokens(
                    functionName: 'tolower',
                    identifier: 'identifier',
                ),
            ],
            'toupper' => [
                new ToUpper(
                    Property::from('identifier'),
                ),
                ...self::createUnaryFunctionTokens(
                    functionName: 'toupper',
                    identifier: 'identifier',
                ),
            ],
            'trim' => [
                new Trim(
                    Property::from('identifier'),
                ),
                ...self::createUnaryFunctionTokens(
                    functionName: 'trim',
                    identifier: 'identifier',
                ),
            ],
        ];
    }

    /**
     * @return Token[]
     */
    public static function createUnaryFunctionTokens(string $functionName, string $identifier): array
    {
        return [
            new ValueToken(
                TokenKind::Function,
                $functionName,
            ),
            new OperatorToken(TokenKind::OpenParen),
            new ValueToken(
                TokenKind::Identifier,
                $identifier,
            ),
            new OperatorToken(TokenKind::CloseParen),
        ];
    }

    /**
     * @return Token[]
     */
    public static function createBinaryFunctionTokens(string $functionName, string $identifier, string $value): array
    {
        return [
            new ValueToken(
                TokenKind::Function,
                $functionName,
            ),
            new OperatorToken(TokenKind::OpenParen),
            new ValueToken(
                TokenKind::Identifier,
                $identifier,
            ),
            new OperatorToken(TokenKind::Comma),
            new ValueToken(
                TokenKind::String,
                $value,
            ),
            new OperatorToken(TokenKind::CloseParen),
        ];
    }
}
