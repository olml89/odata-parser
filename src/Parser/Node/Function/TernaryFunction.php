<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionNode
 */
abstract readonly class TernaryFunction
{
    use IsFunction;
    use HasMultipleOperands;

    /**
     * https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
     */
    final public function __construct(
        public Property|FunctionNode $operand,
        public Node $leftArgument,
        public Node $rightArgument,
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
