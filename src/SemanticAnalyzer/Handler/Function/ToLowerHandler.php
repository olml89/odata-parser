<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\StringHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class ToLowerHandler implements StringHandler
{
    public function __construct(
        private ToLower $toLower,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): StringValue
    {
        return $deferredResolver->string($this->toLower->operand, $visitor)->toLower();
    }
}
