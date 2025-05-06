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
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class LogicalProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'not' => [
                new NotExpression(
                    new Equal(
                        Property::from('identifier'),
                        new Literal(new StringValue('abcde')),
                    ),
                ),
                new OperatorToken(TokenKind::Not),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(
                    TokenKind::String,
                    'abcde',
                ),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'and' => [
                new AndExpression(
                    new Equal(
                        Property::from('identifier'),
                        new Literal(new StringValue('abcde')),
                    ),
                    new Equal(
                        Property::from('identifier'),
                        new Literal(new StringValue('xyz')),
                    ),
                ),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(
                    TokenKind::String,
                    'abcde',
                ),
                new OperatorToken(TokenKind::And),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(
                    TokenKind::String,
                    'xyz',
                ),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'or' => [
                new OrExpression(
                    new Equal(
                        Property::from('identifier'),
                        new Literal(new StringValue('abcde')),
                    ),
                    new Equal(
                        Property::from('identifier'),
                        new Literal(new StringValue('xyz')),
                    ),
                ),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(
                    TokenKind::String,
                    'abcde',
                ),
                new OperatorToken(TokenKind::Or),
                new ValueToken(
                    TokenKind::Identifier,
                    'identifier',
                ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(
                    TokenKind::String,
                    'xyz',
                ),
                new OperatorToken(TokenKind::CloseParen),
            ],
        ];
    }
}
