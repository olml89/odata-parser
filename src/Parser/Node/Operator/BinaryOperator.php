<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Node;

abstract readonly class BinaryOperator
{
    use IsOperator;

    public function __construct(
        public Node $left,
        public Node $right,
    ) {
    }

    abstract protected function keyword(): Keyword;

    public function __toString(): string
    {
        return sprintf(
            '%s %s %s',
            $this->wrapNode($this->left),
            $this->keyword()->value,
            $this->wrapNode($this->right),
        );
    }

    protected function wrapNode(Node $node): string
    {
        return !$node->isPrimary() ? '(' . $node . ')' : (string)$node;
    }
}
