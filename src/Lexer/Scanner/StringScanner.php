<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class StringScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws LexerException
     */
    public function scan(): ?ValueToken
    {
        $string = $this->source->consumeString();

        if (is_null($string)) {
            return null;
        }

        return new ValueToken(TokenKind::String, $string);
    }
}
