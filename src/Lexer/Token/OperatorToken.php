<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Token;

final readonly class OperatorToken implements Token
{
    public function __construct(
        public TokenKind $kind,
    ) {
    }

    public function __toString(): string
    {
        return $this->kind->name;
    }
}
