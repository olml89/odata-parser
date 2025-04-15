<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Parser\Exception\ParserException;

final class CastingException extends ParserException
{
    private function __construct(string $value, string $targetType)
    {
        parent::__construct(
            sprintf(
                'Error trying to parse \'%s\' to %s',
                $value,
                $targetType,
            ),
        );
    }

    public static function fromBool(string $value): self
    {
        return new self($value, 'bool');
    }

    public static function fromInt(string $value): self
    {
        return new self($value, 'int');
    }
}
