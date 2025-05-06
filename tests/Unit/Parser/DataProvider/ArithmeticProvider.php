<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;

final readonly class ArithmeticProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'minus' => [
                new Minus(
                    new Literal(new IntValue(12)),
                ),
                ...self::createUnaryOperatorTokens(
                    tokenKind: TokenKind::Minus,
                    numeric: '12',
                ),
            ],
            'mul' => [
                new Mul(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::Mul,
                    numeric: '2',
                ),
            ],
            'div' => [
                new Div(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::Div,
                    numeric: '2',
                ),
            ],
            'divBy' => [
                new DivBy(
                    Property::from('quantity'),
                    new Literal(new FloatValue(2.5)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::DivBy,
                    numeric: '2.5',
                ),
            ],
            'mod' => [
                new Mod(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::Mod,
                    numeric: '2',
                ),
            ],
            'add' => [
                new Add(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::Add,
                    numeric: '2',
                ),
            ],
            'sub' => [
                new Sub(
                    Property::from('quantity'),
                    new Literal(new IntValue(2)),
                ),
                ...self::createBinaryOperatorTokens(
                    property: 'quantity',
                    tokenKind: TokenKind::Sub,
                    numeric: '2',
                ),
            ],
        ];
    }

    /**
     * @return Token[]
     */
    public static function createUnaryOperatorTokens(TokenKind $tokenKind, string $numeric): array
    {
        return [
            new OperatorToken($tokenKind),
            new ValueToken(
                TokenKind::Number,
                $numeric,
            ),
        ];
    }

    /**
     * @return Token[]
     */
    public static function createBinaryOperatorTokens(string $property, TokenKind $tokenKind, string $numeric): array
    {
        return [
            new ValueToken(
                TokenKind::Identifier,
                $property,
            ),
            new OperatorToken($tokenKind),
            new ValueToken(
                TokenKind::Number,
                $numeric,
            ),
        ];
    }
}
