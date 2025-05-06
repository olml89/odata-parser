<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\IntHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class LengthHandler implements IntHandler
{
    public function __construct(
        private Length $length,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): IntValue
    {
        return $deferredResolver->string($this->length->operand, $visitor)->length();
    }
}
