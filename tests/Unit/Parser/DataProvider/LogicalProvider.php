<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Operator\Logical\AndOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\NotOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\OrOperator;
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
                new NotOperator(
                    new Equal(
                        new Property('identifier'),
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
                new AndOperator(
                    new Equal(
                        new Property('identifier'),
                        new Literal(new StringValue('abcde')),
                    ),
                    new Equal(
                        new Property('identifier'),
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
                new OrOperator(
                    new Equal(
                        new Property('identifier'),
                        new Literal(new StringValue('abcde')),
                    ),
                    new Equal(
                        new Property('identifier'),
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
