<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class ComparisonOperatorProvider implements InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'equal' => [
                'eq',
                new OperatorToken(TokenKind::Equal),
            ],
            'not equal' => [
                'ne',
                new OperatorToken(TokenKind::NotEqual),
            ],
            'greater than' => [
                'gt',
                new OperatorToken(TokenKind::GreaterThan),
            ],
            'greater than or equal' => [
                'ge',
                new OperatorToken(TokenKind::GreaterThanOrEqual),
            ],
            'less than' => [
                'lt',
                new OperatorToken(TokenKind::LessThan)
            ],
            'less than or equal' => [
                'le',
                new OperatorToken(TokenKind::LessThanOrEqual),
            ],
            'in' => [
                'in',
                new OperatorToken(TokenKind::In),
            ],
            'has' => [
                'has',
                new OperatorToken(TokenKind::Has),
            ],
        ];
    }
}
