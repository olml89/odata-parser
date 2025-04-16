<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Exception\InvalidCharLengthException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class IdentifierScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws CharOutOfBoundsException
     * @throws InvalidCharLengthException
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
