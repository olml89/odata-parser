<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

/**
 * @mixin Keyword
 */
trait IsNotChar
{
    public function length(): int
    {
        return mb_strlen($this->value);
    }
}
