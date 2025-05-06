<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison;

use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class EqualHandler implements PredicateHandler
{
    public function __construct(
        private Equal $equal,
    ) {
    }

    /**
     * @throws NodeTypeException
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $left = $deferredResolver->value($this->equal->left, $visitor);
        $right = $deferredResolver->value($this->equal->right, $visitor);

        return $left->eq($right);
    }
}
