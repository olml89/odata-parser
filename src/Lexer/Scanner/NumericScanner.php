<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class NumericScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws CharOutOfBoundsException
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
