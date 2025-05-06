<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class GreaterThanOrEqualHandler implements PredicateHandler
{
    public function __construct(
        private GreaterThanOrEqual $greaterThanOrEqual,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $left = $deferredResolver->number($this->greaterThanOrEqual->left, $visitor);
        $right = $deferredResolver->number($this->greaterThanOrEqual->right, $visitor);

        return $left->ge($right);
    }
}
