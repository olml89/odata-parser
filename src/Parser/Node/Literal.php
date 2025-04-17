<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Parser\Node\Value\Value;

final readonly class Literal implements Node
{
    public function __construct(
        public ?Value $value,
    ) {
    }

    public function isPrimary(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return is_null($this->value) ? TypeConstant::null->value : (string)$this->value;
    }
}
