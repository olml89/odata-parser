<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionExpression
 */
trait IsUnaryFunction
{
    use IsFunction;

    public function __construct(
        public readonly Property|FunctionExpression $operand,
    ) {
    }

    /**
     * @throws NodeTypeException
     */
    public static function invoke(?Node $operand): static
    {
        return new static(self::validateOperand($operand));
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s)',
            static::name()->value,
            $this->operand,
        );
    }
}
