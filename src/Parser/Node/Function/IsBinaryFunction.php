<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionExpression
 */
trait IsBinaryFunction
{
    use IsFunction;
    use HasMultipleOperands;

    public function __construct(
        public readonly Property|FunctionExpression $operand,
        public readonly Literal|FunctionExpression $argument,
    ) {
    }

    protected static function getOperandsCount(): int
    {
        return 2;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s, %s)',
            static::name()->value,
            $this->operand,
            $this->argument,
        );
    }
}
