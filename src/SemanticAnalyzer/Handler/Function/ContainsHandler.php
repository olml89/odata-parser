<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class ContainsHandler implements PredicateHandler
{
    public function __construct(
        private Contains $contains,
    ) {
    }

    /**
     * @throws ValueTypeException
     * @throws NodeTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $haystack = $deferredResolver->container($this->contains->operand, $visitor);

        $needle = $haystack instanceof StringValue
            ? $deferredResolver->string($this->contains->argument, $visitor)
            : $deferredResolver->scalar($this->contains->argument, $visitor);

        return $haystack->contains($needle);
    }
}
