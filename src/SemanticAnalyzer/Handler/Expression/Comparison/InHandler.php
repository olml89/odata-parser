<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class InHandler implements PredicateHandler
{
    public function __construct(
        private In $in,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $haystack = new ScalarCollection(
            ...array_map(
                fn (Literal $literal): Scalar => $deferredResolver->scalar($literal, $visitor),
                $this->in->values,
            ),
        );

        $needle = $deferredResolver->scalar($this->in->property, $visitor);

        return $haystack->contains($needle);
    }
}
