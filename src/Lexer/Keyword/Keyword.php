<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

interface Keyword
{
    public string $value { get; }

    public function length(): int;
}
