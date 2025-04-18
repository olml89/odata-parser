<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Logical;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Operator\BinaryOperator;

final readonly class OrOperator extends BinaryOperator implements Node
{
    protected function keyword(): Keyword
    {
        return LogicalOperator::or;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s %s',
            $this->wrapNode($this->left),
            $this->keyword()->value,
            $this->wrapNode($this->right),
        );
    }
}
