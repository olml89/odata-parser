<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

final class OutOfBoundsException extends ParserException
{
    public function __construct(int $position, int $count)
    {
        parent::__construct(
            sprintf(
                'Token out of bounds, position: %s from %s',
                $position,
                $count - 1,
            ),
        );
    }
}
