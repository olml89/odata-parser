<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;

final class UnexpectedTokenException extends ParserException
{
    public static function position(Token $token, int $position): self
    {
        return new self(
            sprintf(
                'Unexpected %s: %s at position %s',
                $token::class,
                $token->kind->name,
                $position,
            ),
        );
    }

    public static function wrongTokenKind(Token $token, TokenKind ...$expectedTokenKinds): self
    {
        return new self(
            sprintf(
                'Unexpected token: %s, instead of: %s',
                $token->kind->name,
                count($expectedTokenKinds) === 1
                    ? current($expectedTokenKinds)->name
                    : implode(
                        ', ',
                        array_map(fn (TokenKind $kind): string => $kind->name, $expectedTokenKinds),
                    ),
            ),
        );
    }
}
