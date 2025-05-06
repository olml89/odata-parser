<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\PredicateHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;

final readonly class DeferredPredicate implements Deferred
{
    public function __construct(
        private PredicateHandler $handler,
        private PredicateBuilderVisitor $visitor,
    ) {
    }

    public function executeInScope(LocalScopeContext $localScopeContext): BoolValue
    {
        return $this->visitor->executeInScope($localScopeContext, $this);
    }

    public function fetch(mixed $subject): BoolValue
    {
        return $this->handler->handle(
            visitor: $this->visitor,
            deferredResolver: new DeferredResolver($subject),
        );
    }
}
