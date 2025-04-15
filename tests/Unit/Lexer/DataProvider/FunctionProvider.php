<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class FunctionProvider implements InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'concat' => [
                'concat',
                new ValueToken(TokenKind::Function, 'concat'),
            ],
            'contains' => [
                'contains',
                new ValueToken(TokenKind::Function, 'contains'),
            ],
            'endswith' => [
                'endswith',
                new ValueToken(TokenKind::Function, 'endswith'),
            ],
            'indexof' => [
                'indexof',
                new ValueToken(TokenKind::Function, 'indexof'),
            ],
            'length' => [
                'length',
                new ValueToken(TokenKind::Function, 'length'),
            ],
            'matchesPattern' => [
                'matchesPattern',
                new ValueToken(TokenKind::Function, 'matchesPattern'),
            ],
            'startswith' => [
                'startswith',
                new ValueToken(TokenKind::Function, 'startswith'),
            ],
            'substring' => [
                'substring',
                new ValueToken(TokenKind::Function, 'substring'),
            ],
            'tolower' => [
                'tolower',
                new ValueToken(TokenKind::Function, 'tolower'),
            ],
            'toupper' => [
                'toupper',
                new ValueToken(TokenKind::Function, 'toupper'),
            ],
            'trim' => [
                'trim',
                new ValueToken(TokenKind::Function, 'trim'),
            ],
        ];
    }
}
