<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class CollectionOperatorProvider implements InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'any' => [
                'any',
                new OperatorToken(TokenKind::Any),
            ],
            'all' => [
                'all',
                new OperatorToken(TokenKind::All),
            ],
        ];
    }
}
