<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Parser\Exception\TokenOutOfBoundsException;

final class TokenManager
{
    /**
     * @var Token[]
     */
    private readonly array $tokens;

    private int $position = 0;

    public function __construct(Token ...$tokens)
    {
        $this->tokens = $tokens;
    }

    public function count(): int
    {
        return count($this->tokens);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function eof(): bool
    {
        return $this->isEmpty() || $this->position >= $this->count() - 1;
    }

    private function advance(): void
    {
        if ($this->eof()) {
            return;
        }

        ++$this->position;
    }

    /**
     * @param int $position
     * @return TokenWrapper
     * @throws TokenOutOfBoundsException
     */
    private function get(int $position): TokenWrapper
    {
        $token = $this->tokens[$position] ?? null;

        if (is_null($token)) {
            throw new TokenOutOfBoundsException($position, $this->count());
        }

        return new TokenWrapper(
            token: $token,
            position: $position,
            advanceTokenPosition: fn () => $this->advance(),
        );
    }

    /**
     * @throws TokenOutOfBoundsException
     */
    public function peek(): TokenWrapper
    {
        return $this->get($this->position);
    }

    /**
     * @throws TokenOutOfBoundsException
     */
    public function next(): TokenWrapper
    {
        return $this->get($this->position + 1);
    }
}
