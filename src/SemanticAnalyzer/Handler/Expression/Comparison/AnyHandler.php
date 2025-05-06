<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Exception\InvalidAstException;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;

final readonly class AnyHandler implements PredicateHandler
{
    public function __construct(
        private Any $any,
    ) {
    }

    /**
     * @throws ValueTypeException
     * @throws InvalidAstException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $collection = $deferredResolver->scopedCollection($this->any->property, $visitor);
        $predicate = $this->any->predicate->accept($visitor);

        if (!($predicate instanceof DeferredPredicate)) {
            throw new InvalidAstException($this->any->predicate);
        }

        return $collection->any($this->any->variable, $predicate);
    }
}
