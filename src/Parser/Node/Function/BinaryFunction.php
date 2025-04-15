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
    /**
     * https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
     */
    final public function __construct(
        public Property|UnaryFunction $property,
        public Node $argument,
    ) {
    }

    /**
     * @param Node[] $arguments
     *
     * @throws ArgumentCountException
     */
    public static function invoke(Property|UnaryFunction $property, array $arguments): static
    {
        if (count($arguments) === 0) {
            throw new ArgumentCountException(
                functionName: static::name(),
                providedArgumentsCount: count($arguments),
                neededArgumentsCount: 2,
            );
        }

        return new static($property, ...$arguments);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s, %s)',
            static::name()->value,
            $this->property,
            $this->argument,
        );
    }
}
