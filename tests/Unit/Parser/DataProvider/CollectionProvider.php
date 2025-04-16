<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\Comparison\All;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Any;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Operator\Comparison\GreaterThan;
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
                    property: new Property('tags'),
                    variable: new Property('t'),
                    predicate: new Equal(
                        new Property('t'),
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
            'any accessing property entities' => [
                new Any(
                    property: new Property('orders'),
                    variable: new Property('o'),
                    predicate: new GreaterThan(
                        new Property('o', new Property('amount')),
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
            'all accessing entities' => [
                new All(
                    property: new Property('tags'),
                    variable: new Property('t'),
                    predicate: new Equal(
                        new Property('t'),
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
            'all accessing property entities' => [
                new All(
                    property: new Property('orders'),
                    variable: new Property('o'),
                    predicate: new GreaterThan(
                        new Property('o', new Property('amount')),
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
        ];
    }
}
