<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\StringHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class SubstringHandler implements StringHandler
{
    public function __construct(
        private Substring $substring,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): StringValue
    {
        $string = $deferredResolver->string($this->substring->operand, $visitor);
        $start = $deferredResolver->int($this->substring->leftArgument, $visitor);
        $length = $deferredResolver->tryInt($this->substring->rightArgument, $visitor);

        return $string->substring($start, $length);
    }
}
