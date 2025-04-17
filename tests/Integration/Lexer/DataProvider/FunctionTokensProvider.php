<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class FunctionTokensProvider implements InputAndExpectedTokensProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'concat' => [
                'concat(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'concat'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'contains' => [
                'contains(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'contains'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'endswith' => [
                'endswith(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'endswith'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'indexof' => [
                'indexof(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'indexof'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'length' => [
                'length(name)',
                new ValueToken(TokenKind::Function, 'length'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'matchesPattern' => [
                'matchesPattern(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'matchesPattern'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'startswith' => [
                'startswith(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'startswith'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'substring' => [
                'substring(name, \'abc\')',
                new ValueToken(TokenKind::Function, 'substring'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::Comma),
                new ValueToken(TokenKind::String, 'abc'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'tolower' => [
                'tolower(name)',
                new ValueToken(TokenKind::Function, 'tolower'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'toupper' => [
                'toupper(name)',
                new ValueToken(TokenKind::Function, 'toupper'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::CloseParen),
            ],
            'trim' => [
                'trim(name)',
                new ValueToken(TokenKind::Function, 'trim'),
                new OperatorToken(TokenKind::OpenParen),
                new ValueToken(TokenKind::Identifier, 'name'),
                new OperatorToken(TokenKind::CloseParen),
            ],
        ];
    }
}
