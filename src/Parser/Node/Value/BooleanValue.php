<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Lexer\Keyword\TypeConstant;

final readonly class BooleanValue extends Value
{
    public function __construct(
        public bool $value,
    ) {
    }

    public function __toString(): string
    {
        return ($this->value) ? TypeConstant::true->value : TypeConstant::false->value ;
    }
}
