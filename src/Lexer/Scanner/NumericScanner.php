<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class NumericScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws LexerException
     */
    public function scan(): ?ValueToken
    {
        $numeric = $this->source->consumeNumeric();

        if (is_null($numeric)) {
            return null;
        }

        return new ValueToken(TokenKind::Number, $numeric);
    }
}
