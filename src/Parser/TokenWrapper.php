<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use Closure;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;

final readonly class TokenWrapper
{
    public function __construct(
        public Token $token,
        private Closure $advanceTokenPosition,
    ) {
    }

    public function is(TokenKind ...$tokenKinds): bool
    {
        return $this->token->kind->is(...$tokenKinds);
    }

    public function consume(TokenKind ...$tokenKinds): bool
    {
        if ($this->is(...$tokenKinds)) {
            ($this->advanceTokenPosition)();

            return true;
        }

        return false;
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function expect(TokenKind $tokenKind): void
    {
        if (!$this->consume($tokenKind)) {
            throw UnexpectedTokenException::wrongTokenKind(
                $this->token,
                expectedTokenKind: $tokenKind,
            );
        }
    }
}
