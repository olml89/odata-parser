<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator;

use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

abstract readonly class CollectionLambdaOperator
{
    use IsOperator;

    public function __construct(
        public Property $property,
        public Property $variable,
        public Node $predicate,
    ) {
    }

    abstract protected function keyword(): Keyword;

    public function __toString(): string
    {
        return sprintf(
            '%s/%s(%s: %s)',
            $this->property,
            $this->keyword()->value,
            $this->variable,
            $this->predicate,
        );
    }
}
