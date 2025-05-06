<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Function;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;
use olml89\ODataParser\Parser\Node\Property;

/**
 * @mixin FunctionExpression
 */
trait IsFunction
{
    public NodeType $type {
        get => NodeType::Function;
    }

    abstract protected static function name(): FunctionName;

    /**
     * @throws NodeTypeException
     */
    protected static function validateOperand(?Node $operand): Property|FunctionExpression
    {
        if (!($operand instanceof Property) && !($operand instanceof FunctionExpression)) {
            throw new NodeTypeException(
                $operand,
                NodeType::Property,
                NodeType::Function,
            );
        }

        return $operand;
    }
}
