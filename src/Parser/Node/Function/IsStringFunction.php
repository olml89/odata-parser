<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Parser\Exception\LiteralTypeException;
use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;

trait IsStringFunction
{
    /**
     * @return array<int, Literal|FunctionExpression>
     *
     * @throws NodeTypeException
     * @throws LiteralTypeException
     */
    protected static function validateArguments(Node ...$arguments): array
    {
        foreach ($arguments as $argument) {
            if (!($argument instanceof Literal) && !($argument instanceof FunctionExpression)) {
                throw new NodeTypeException(
                    $argument,
                    NodeType::Literal,
                    NodeType::Function,
                );
            }

            if ($argument instanceof Literal && !($argument->value instanceof StringValue)) {
                throw new LiteralTypeException(
                    $argument,
                    ValueType::String,
                );
            }
        }

        /** @var array<int, Literal|FunctionExpression> $arguments */
        return $arguments;
    }
}
