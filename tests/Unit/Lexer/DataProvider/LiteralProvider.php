<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class LiteralProvider implements InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'null' => [
                'null',
                new ValueToken(TokenKind::Null, 'null'),
            ],
            'true' => [
                'true',
                new ValueToken(TokenKind::Boolean, 'true'),
            ],
            'false' => [
                'false',
                new ValueToken(TokenKind::Boolean, 'false'),
            ],
            'identifier' => [
                'name',
                new ValueToken(TokenKind::Identifier, 'name'),
            ],
            'int' => [
                '12',
                new ValueToken(TokenKind::Number, '12'),
            ],
            'float' => [
                '12.1564',
                new ValueToken(TokenKind::Number, '12.1564'),
            ],
            'string between simple quotes' => [
                "'Little John'",
                new ValueToken(TokenKind::String, 'Little John'),
            ],
            'string between double quotes' => [
                '"Little John"',
                new ValueToken(TokenKind::String, 'Little John'),
            ],
        ];
    }
}
