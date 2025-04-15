<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\TokenKind;

final readonly class SpecialCharScanner implements Scanner
{
    use IsScanner;

    public function scan(): ?OperatorToken
    {
        $specialChar = $this->source->find(
            SpecialChar::OpenParen,
            SpecialChar::CloseParen,
            SpecialChar::Comma,
        );

        $tokenKind = match ($specialChar) {
            SpecialChar::OpenParen => TokenKind::OpenParen,
            SpecialChar::CloseParen => TokenKind::CloseParen,
            SpecialChar::Comma => TokenKind::Comma,
            default => null,
        };

        if (is_null($tokenKind)) {
            return null;
        }

        return new OperatorToken($tokenKind);
    }
}
