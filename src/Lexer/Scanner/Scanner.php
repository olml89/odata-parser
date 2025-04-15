<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Token\Token;

interface Scanner
{
    public function scan(): ?Token;
}
