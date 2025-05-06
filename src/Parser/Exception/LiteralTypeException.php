<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Value\ValueType;

final class LiteralTypeException extends ParserException
{
    public function __construct(Literal $literal, ValueType ...$expectedTypes)
    {
        parent::__construct(
            sprintf(
                'Literal of type %s expected, got %s with type %s',
                implode(
                    ' or ',
                    array_map(
                        fn (ValueType $expectedType): string => $expectedType->value,
                        $expectedTypes,
                    ),
                ),
                $literal->value->value(),
                $literal->value::type()->value,
            ),
        );
    }
}
