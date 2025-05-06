<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionExpression
 */
trait IsTernaryFunction
{
    use IsFunction;
    use HasMultipleOperands;

    public function __construct(
        public readonly Property|FunctionExpression $operand,
        public readonly Literal|FunctionExpression $leftArgument,
        public readonly Literal|FunctionExpression $rightArgument,
    ) {
    }

    protected static function getOperandsCount(): int
    {
        return 3;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s, %s, %s)',
            static::name()->value,
            $this->operand,
            $this->leftArgument,
            $this->rightArgument,
        );
    }
}
