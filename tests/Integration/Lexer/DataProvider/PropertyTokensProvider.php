<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class PropertyTokensProvider implements InputAndExpectedTokensProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array
    {
        return [
            'property' => [
                'name',
                new ValueToken(TokenKind::Identifier, 'name'),
            ],
            'property with sub-property' => [
                'geolocation/latitude',
                new ValueToken(TokenKind::Identifier, 'geolocation'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'latitude'),
            ],
            'property with sub-property preceded by a hyphen interpreted as a minus' => [
                '-geolocation/latitude',
                new OperatorToken(TokenKind::Minus),
                new ValueToken(TokenKind::Identifier, 'geolocation'),
                new OperatorToken(TokenKind::Slash),
                new ValueToken(TokenKind::Identifier, 'latitude'),
            ],
        ];
    }
}
