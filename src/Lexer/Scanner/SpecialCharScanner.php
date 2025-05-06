<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class SpecialCharScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws CharOutOfBoundsException
     */
    public function scan(): ?OperatorToken
    {
        $specialChar = $this->source->find(
            SpecialChar::Minus,
            SpecialChar::OpenParen,
            SpecialChar::CloseParen,
            SpecialChar::Comma,
            SpecialChar::Colon,
            SpecialChar::Slash,
        );

        $tokenKind = match ($specialChar) {
            SpecialChar::Minus => TokenKind::Minus,
            SpecialChar::OpenParen => TokenKind::OpenParen,
            SpecialChar::CloseParen => TokenKind::CloseParen,
            SpecialChar::Comma => TokenKind::Comma,
            SpecialChar::Colon => TokenKind::Colon,
            SpecialChar::Slash => TokenKind::Slash,
            default => null,
        };

        if (is_null($tokenKind)) {
            return null;
        }

        return new OperatorToken($tokenKind);
    }
}
