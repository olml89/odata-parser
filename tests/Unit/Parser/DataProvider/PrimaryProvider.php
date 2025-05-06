<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\DataProvider;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;

final readonly class PrimaryProvider implements NodeAndExpectedTokensProvider
{
    /**
     * @return array<string, non-empty-array<Node|Token>>
     */
    public static function provide(): array
    {
        return [
            'identifier' => [
                Property::from('identifier'),
                new ValueToken(TokenKind::Identifier, 'identifier'),
            ],
            'null' => [
                new Literal(new NullValue()),
                new ValueToken(TokenKind::Null, 'null'),
            ],
            'true' => [
                new Literal(new BoolValue(true)),
                new ValueToken(TokenKind::Boolean, 'true'),
            ],
            'false' => [
                new Literal(new BoolValue(false)),
                new ValueToken(TokenKind::Boolean, 'false'),
            ],
            'int' => [
                new Literal(new IntValue(12)),
                new ValueToken(TokenKind::Number, '12'),
            ],
            'float' => [
                new Literal(new FloatValue(12.24523)),
                new ValueToken(TokenKind::Number, '12.24523'),
            ],
            'string' => [
                new Literal(new StringValue('John Smith')),
                new ValueToken(TokenKind::String, 'John Smith'),
            ],
        ];
    }
}
