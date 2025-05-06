<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler\Function;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Exception\InvalidRegExpException;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class MatchesPatternHandler implements PredicateHandler
{
    public function __construct(
        private MatchesPattern $matchesPattern,
    ) {
    }

    /**
     * @throws ValueTypeException
     * @throws InvalidRegExpException
     */
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): BoolValue
    {
        $subject = $deferredResolver->string($this->matchesPattern->operand, $visitor);
        $pattern = $deferredResolver->string($this->matchesPattern->argument, $visitor);

        return $subject->matchRegExp($pattern);
    }
}
