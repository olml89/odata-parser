<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Parser\Node\Node;

interface FunctionNode extends Node
{
    public static function name(): FunctionName;
}
