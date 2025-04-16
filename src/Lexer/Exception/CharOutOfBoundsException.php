<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Exception;

final class CharOutOfBoundsException extends LexerException
{
    public function __construct(int $position, int $count)
    {
        parent::__construct(
            sprintf(
                'Character out of bounds, position: %s from %s',
                $position,
                $count - 1,
            ),
        );
    }
}
