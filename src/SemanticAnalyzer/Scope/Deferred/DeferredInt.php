<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\IntHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class DeferredInt implements Deferred
{
    public function __construct(
        private IntHandler $handler,
        private PredicateBuilderVisitor $visitor,
    ) {
    }

    public function fetch(mixed $subject): IntValue
    {
        return $this->handler->handle(
            visitor: $this->visitor,
            deferredResolver: new DeferredResolver($subject),
        );
    }
}
