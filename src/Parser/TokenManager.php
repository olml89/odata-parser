<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser;

use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Parser\Exception\OutOfBoundsException;

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
     * @throws OutOfBoundsException
     */
    public function peek(): TokenWrapper
    {
        $token = $this->tokens[$this->position] ?? null;

        if (is_null($token)) {
            throw new OutOfBoundsException($this->position, $this->count());
        }

        return new TokenWrapper(
            token: $token,
            position: $this->position,
            advanceTokenPosition: fn () => $this->advance(),
        );
    }
}
