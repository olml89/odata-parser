<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class SpecialCharTokensProvider implements InputAndExpectedTokensProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'open parentheses' => [
                '(',
                new OperatorToken(TokenKind::OpenParen),
            ],
            'close parentheses' => [
                ')',
                new OperatorToken(TokenKind::CloseParen),
            ],
            'comma' => [
                ',',
                new OperatorToken(TokenKind::Comma),
            ],
            'colon' => [
                ':',
                new OperatorToken(TokenKind::Colon),
            ],
            'slash' => [
                '/',
                new OperatorToken(TokenKind::Slash),
            ],
        ];
    }
}
