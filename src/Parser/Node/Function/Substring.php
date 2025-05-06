<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Parser\Exception\LiteralTypeException;
use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class Substring implements FunctionExpression
{
    use IsTernaryFunction;

    protected static function name(): FunctionName
    {
        return FunctionName::substring;
    }

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

            if ($argument instanceof Literal && !($argument->value instanceof IntValue)) {
                throw new LiteralTypeException(
                    $argument,
                    ValueType::Int,
                );
            }
        }

        /** @var array<int, Literal|FunctionExpression> $arguments */
        return $arguments;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitSubstring($this);
    }
}
