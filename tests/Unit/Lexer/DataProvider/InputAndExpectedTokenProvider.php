<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\Token;

interface InputAndExpectedTokenProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array;
}
