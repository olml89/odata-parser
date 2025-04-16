<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Exception\UnterminatedStringException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class StringScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws CharOutOfBoundsException
     * @throws UnterminatedStringException
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
