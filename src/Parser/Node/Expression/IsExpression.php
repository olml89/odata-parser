<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;

/**
 * @mixin Expression
 */
trait IsExpression
{
    public NodeType $type {
        get => NodeType::Expression;
    }

    abstract protected function keyword(): Keyword;

    protected function wrapNode(Node $node): string
    {
        return $node instanceof Expression ? '(' . $node . ')' : (string)$node;
    }
}
