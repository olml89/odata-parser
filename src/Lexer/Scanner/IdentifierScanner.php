<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class IdentifierScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws LexerException
     */
    public function scan(): ?ValueToken
    {
        $identifier = $this->source->consumeAlpha();

        if (is_null($identifier)) {
            return null;
        }

        return new ValueToken(TokenKind::Identifier, $identifier);
    }
}
