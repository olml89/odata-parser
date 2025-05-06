<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\IntHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class IndexOfHandler implements IntHandler
{
    public function __construct(
        private IndexOf $indexOf,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): IntValue
    {
        $haystack = $deferredResolver->string($this->indexOf->operand, $visitor);
        $needle = $deferredResolver->string($this->indexOf->argument, $visitor);

        return $haystack->indexOf($needle);
    }
}
