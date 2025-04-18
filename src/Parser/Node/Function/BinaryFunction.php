<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionNode
 */
abstract readonly class BinaryFunction
{
    use IsFunction;
    use HasMultipleOperands;

    /**
     * https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
     */
    final public function __construct(
        public Property|FunctionNode $operand,
        public Node $argument,
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
