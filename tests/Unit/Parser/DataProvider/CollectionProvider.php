<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\Comparison\All;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class CollectionProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'any accessing entities' => [
                new Any(
                    property: Property::from('tags'),
                    variable: Property::from('t'),
                    predicate: new Equal(
                        Property::from('t'),
                        new Literal(new StringValue('yes')),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'tags'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::Any),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 't'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 't'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'yes'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'any accessing entity properties' => [
                new Any(
                    property: Property::from('orders'),
                    variable: Property::from('o'),
                    predicate: new GreaterThan(
                        Property::from('o', Property::from('amount')),
                        new Literal(new IntValue(100)),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'orders'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::Any),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'o'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 'o'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'amount'),
                new OperatorToken(TokenKind::GreaterThan),
                new ValueToken(TokenKind::Number, '100'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'any accessing nested entity properties' => [
                new Any(
                    property: Property::from('order', Property::from('items')),
                    variable: Property::from('i'),
                    predicate: new GreaterThan(
                        Property::from('i', Property::from('invoice', Property::from('amount'))),
                        new Literal(new IntValue(100)),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'order'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'items'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::Any),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'i'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 'i'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'invoice'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'amount'),
                new OperatorToken(TokenKind::GreaterThan),
                new ValueToken(TokenKind::Number, '100'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'all accessing entities' => [
                new All(
                    property: Property::from('tags'),
                    variable: Property::from('t'),
                    predicate: new Equal(
                        Property::from('t'),
                        new Literal(new StringValue('yes')),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'tags'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::All),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 't'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 't'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'yes'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'all accessing entity properties' => [
                new All(
                    property: Property::from('orders'),
                    variable: Property::from('o'),
                    predicate: new GreaterThan(
                        Property::from('o', Property::from('amount')),
                        new Literal(new IntValue(100)),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'orders'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::All),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'o'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 'o'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'amount'),
                new OperatorToken(TokenKind::GreaterThan),
                new ValueToken(TokenKind::Number, '100'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'all accessing nested entity properties' => [
                new All(
                    property: Property::from('order', Property::from('items')),
                    variable: Property::from('i'),
                    predicate: new GreaterThan(
                        Property::from('i', Property::from('invoice', Property::from('amount'))),
                        new Literal(new IntValue(100)),
                    )
                ),
                new ValueToken(TokenKind::Identifier, 'order'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'items'),
                new OperatorToken(TokenKind::Slash),
                new OperatorToken(TokenKind::All),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'i'),
                new OperatorToken(TokenKind::Colon),
                new ValueToken(TokenKind::Identifier, 'i'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'invoice'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'amount'),
                new OperatorToken(TokenKind::GreaterThan),
                new ValueToken(TokenKind::Number, '100'),
                new OperatorToken(TokenKind::CloseParen),
            ],
        ];
    }
}
