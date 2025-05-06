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
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class SubExpressionProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'and takes precedence (evaluated first, inner) on nested ands and ors at the beginning of the 
            expression' => [
                new OrExpression(
                    new AndExpression(
                        new Equal(
                            Property::from('name'),
                            new Literal(new StringValue('John Smith')),
                        ),
                        new Equal(
                            Property::from('quantity'),
                            new Literal(new IntValue(2)),
                        ),
                    ),
                    new NotEqual(
                        Property::from('accepted'),
                        new Literal(new BoolValue(true)),
                    ),
                ),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name', ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'John Smith'),
                new OperatorToken(TokenKind::And),
                new ValueToken(TokenKind::Identifier, 'quantity'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::Number, '2'),
                new OperatorToken(TokenKind::Or),
                new ValueToken(TokenKind::Identifier, 'accepted'),
                new OperatorToken(TokenKind::NotEqual),
                new ValueToken(TokenKind::Boolean, 'true'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'or takes precedence (evaluated first, inner) on nested ands and ors at the beginning of the expression 
            if indicated by parentheses' => [
                new AndExpression(
                    new Equal(
                        Property::from('name'),
                        new Literal(new StringValue('John Smith')),
                    ),
                    new OrExpression(
                        new Equal(
                            Property::from('quantity'),
                            new Literal(new IntValue(2)),
                        ),
                        new NotEqual(
                            Property::from('accepted'),
                            new Literal(new BoolValue(true)),
                        ),
                    ),
                ),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name', ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'John Smith'),
                new OperatorToken(TokenKind::And),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'quantity'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::Number, '2'),
                new OperatorToken(TokenKind::Or),
                new ValueToken(TokenKind::Identifier, 'accepted'),
                new OperatorToken(TokenKind::NotEqual),
                new ValueToken(TokenKind::Boolean, 'true'),
                new OperatorToken(TokenKind::CloseParen),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'and takes precedence (evaluated first, inner) on nested ands and ors at the middle of the expression' => [
                new OrExpression(
                    new AndExpression(
                        new Equal(
                            Property::from('name'),
                            new Literal(new StringValue('John Smith')),
                        ),
                        new Equal(
                            Property::from('quantity'),
                            new Literal(new IntValue(2)),
                        ),
                    ),
                    new NotEqual(
                        Property::from('accepted'),
                        new Literal(new BoolValue(true)),
                    ),
                ),
                new ValueToken(TokenKind::Identifier, 'name', ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'John Smith'),
                new OperatorToken(TokenKind::And),
                new ValueToken(TokenKind::Identifier, 'quantity'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::Number, '2'),
                new OperatorToken(TokenKind::Or),
                new ValueToken(TokenKind::Identifier, 'accepted'),
                new OperatorToken(TokenKind::NotEqual),
                new ValueToken(TokenKind::Boolean, 'true'),
            ],
            'or takes precedence (evaluated first, inner) on nested ands and ors at the middle of the expression if 
            indicated by parentheses' => [
                new AndExpression(
                    new Equal(
                        Property::from('name'),
                        new Literal(new StringValue('John Smith')),
                    ),
                    new OrExpression(
                        new Equal(
                            Property::from('quantity'),
                            new Literal(new IntValue(2)),
                        ),
                        new NotEqual(
                            Property::from('accepted'),
                            new Literal(new BoolValue(true)),
                        ),
                    ),
                ),
                new ValueToken(TokenKind::Identifier, 'name', ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::String, 'John Smith'),
                new OperatorToken(TokenKind::And),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'quantity'),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::Number, '2'),
                new OperatorToken(TokenKind::Or),
                new ValueToken(TokenKind::Identifier, 'accepted'),
                new OperatorToken(TokenKind::NotEqual),
                new ValueToken(TokenKind::Boolean, 'true'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'redundant boolean comparison' => [
                new AndExpression(
                    new Equal(
                        Property::from('active'),
                        new Literal(new BoolValue(true)),
                    ),
                    Property::from('active'),
                ),
                new ValueToken(TokenKind::Identifier, 'active', ),
                new OperatorToken(TokenKind::Equal),
                new ValueToken(TokenKind::Boolean, 'true'),
                new OperatorToken(TokenKind::And),
                new ValueToken(TokenKind::Identifier, 'active', ),
            ],
        ];
    }
}
