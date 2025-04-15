<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Source;

trait IsScanner
{
    public function __construct(
        private readonly Source $source,
    ) {
    }
}
