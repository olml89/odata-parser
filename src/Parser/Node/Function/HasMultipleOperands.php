<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionNode
 *
 */
trait HasMultipleOperands
{
    abstract protected static function getOperandsCount(): int;

    /**
     * @param Node[] $arguments
     *
     * @throws ArgumentCountException
     */
    public static function invoke(Property|FunctionNode $property, array $arguments): static
    {
        if (count($arguments) < self::getOperandsCount() - 1) {
            throw new ArgumentCountException(
                functionName: static::name(),
                providedArgumentsCount: count($arguments) + 1,
                neededArgumentsCount: self::getOperandsCount(),
            );
        }

        return new static($property, ...$arguments);
    }
}
