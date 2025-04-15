<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class LogicalOperatorProvider implements InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'not' => [
                'not',
                new OperatorToken(TokenKind::Not),
            ],
            'and' => [
                'and',
                new OperatorToken(TokenKind::And),
            ],
            'or' => [
                'or',
                new OperatorToken(TokenKind::Or),
            ],
        ];
    }
}
