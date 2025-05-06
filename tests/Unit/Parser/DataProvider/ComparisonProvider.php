<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Has;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class ComparisonProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'equal' => [
                new Equal(
                    Property::from('name'),
                    new Literal(new StringValue('John Smith'))
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'name',
                    tokenKind: TokenKind::Equal,
                    valueType: TokenKind::String,
                    value: 'John Smith',
                ),
            ],
            'not equal' => [
                new NotEqual(
                    Property::from('accepted'),
                    new Literal(new BoolValue(true)),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'accepted',
                    tokenKind: TokenKind::NotEqual,
                    valueType: TokenKind::Boolean,
                    value: 'true',
                ),
            ],
            'less than' => [
                new LessThan(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::LessThan,
                    valueType: TokenKind::Number,
                    value: '2',
                ),
            ],
            'less than or equal' => [
                new LessThanOrEqual(
                    Property::from('quantity'),
                    new Literal(new FloatValue(2.5)),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::LessThanOrEqual,
                    valueType: TokenKind::Number,
                    value: '2.5',
                ),
            ],
            'greater than' => [
                new GreaterThan(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::GreaterThan,
                    valueType: TokenKind::Number,
                    value: '2',
                ),
            ],
            'greater than or equal' => [
                new GreaterThanOrEqual(
                    Property::from('quantity'),
                    new Literal(new FloatValue(2.5)),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::GreaterThanOrEqual,
                    valueType: TokenKind::Number,
                    value: '2.5',
                ),
            ],
            'has' => [
                new Has(
                    Property::from('cities'),
                    new Literal(new StringValue('Berlin')),
                ),
                ...self::createComparisonOperatorTokens(
                    property: 'cities',
                    tokenKind: TokenKind::Has,
                    valueType: TokenKind::String,
                    value: 'Berlin',
                ),
            ],
            'in' => [
                new In(
                    Property::from('city'),
                    new Literal(new StringValue('Berlin')),
                    new Literal(new StringValue('Roma')),
                    new Literal(new StringValue('Paris')),
                ),
                new ValueToken(
                    TokenKind::Identifier,
                    'city',
                ),
                new OperatorToken(TokenKind::In),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(
                    TokenKind::String,
                    'Berlin',
                ),
                new ValueToken(
                    TokenKind::String,
                    'Roma',
                ),
                new ValueToken(
                    TokenKind::String,
                    'Paris',
                ),
                new OperatorToken(TokenKind::CloseParen),
            ],
        ];
    }

    /**
     * @return Token[]
     */
    private static function createComparisonOperatorTokens(
        string $property,
        TokenKind $tokenKind,
        TokenKind $valueType,
        string $value,
    ): array {
        return [
            new ValueToken(
                TokenKind::Identifier,
                $property,
            ),
            new OperatorToken($tokenKind),
            new ValueToken(
                $valueType,
                $value,
            ),
        ];
    }
}
