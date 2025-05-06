<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class ArithmeticOperatorTokensProvider implements InputAndExpectedTokensProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'multiplication' => [
                'mul',
                new OperatorToken(TokenKind::Mul),
            ],
            'integer division' => [
                'div',
                new OperatorToken(TokenKind::Div),
            ],
            'division' => [
                'divBy',
                new OperatorToken(TokenKind::DivBy),
            ],
            'modulo' => [
                'mod',
                new OperatorToken(TokenKind::Mod),
            ],
            'addition' => [
                'add',
                new OperatorToken(TokenKind::Add),
            ],
            'subtraction' => [
                'sub',
                new OperatorToken(TokenKind::Sub),
            ],
        ];
    }
}
