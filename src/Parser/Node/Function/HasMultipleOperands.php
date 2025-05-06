<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Exception\ArgumentCountException;
use olml89\ODataParser\Parser\Exception\LiteralTypeException;
use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;

/**
 * @mixin FunctionExpression
 *
 */
trait HasMultipleOperands
{
    abstract protected static function getOperandsCount(): int;

    /**
     * @return array<int, Literal|FunctionExpression>
     *
     * @throws NodeTypeException
     * @throws LiteralTypeException
     */
    abstract protected static function validateArguments(Node ...$arguments): array;

    /**
     * @param Node[] $arguments
     *
     * @throws ArgumentCountException
     * @throws NodeTypeException
     * @throws LiteralTypeException
     */
    public static function invoke(?Node $operand, array $arguments): static
    {
        $operand = self::validateOperand($operand);

        if (count($arguments) < self::getOperandsCount() - 1) {
            throw new ArgumentCountException(
                functionName: static::name(),
                providedArgumentsCount: count($arguments) + 1,
                neededArgumentsCount: self::getOperandsCount(),
            );
        }

        $arguments = static::validateArguments(...$arguments);

        return new static($operand, ...$arguments);
    }
}
