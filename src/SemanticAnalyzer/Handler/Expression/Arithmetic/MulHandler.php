<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\NumberHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class MulHandler implements NumberHandler
{
    public function __construct(
        private Mul $mul,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): Number
    {
        $left = $deferredResolver->number($this->mul->left, $visitor);
        $right = $deferredResolver->number($this->mul->right, $visitor);

        return $left->mul($right);
    }
}
