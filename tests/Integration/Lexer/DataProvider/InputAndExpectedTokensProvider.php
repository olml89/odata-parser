<?php

declare(strict_types=1);

namespace Tests\Integration\Lexer\DataProvider;

use olml89\ODataParser\Lexer\Token\Token;

interface InputAndExpectedTokensProvider
{
    /**
     * @return array<string, array{0: string, 1: Token}>
     */
    public static function provide(): array;
}
