<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Parser\Node\Value\ValueType;
use olml89\ODataParser\SemanticAnalyzer\Exception\SemanticException;
use olml89\ODataParser\SemanticAnalyzer\Scope\ResolvedType;

final class ValueTypeException extends SemanticException
{
    public function __construct(mixed $value, ResolvedType|ValueType ...$expectedTypes)
    {
        parent::__construct(
            sprintf(
                'Invalid value of type %s, expected value of type: %s',
                gettype($value),
                implode(
                    ', ',
                    array_map(
                        fn (ResolvedType|ValueType $expectedType): string => $expectedType->value,
                        $expectedTypes,
                    ),
                ),
            ),
        );
    }
}
