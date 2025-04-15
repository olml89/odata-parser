<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Keyword;

enum CollectionOperator: string implements Keyword
{
    use IsNotChar;

    case any = 'any';
    case all = 'all';
}
