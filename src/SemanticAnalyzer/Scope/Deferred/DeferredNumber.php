<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\NumberHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class DeferredNumber implements Deferred
{
    public function __construct(
        private NumberHandler $handler,
        private PredicateBuilderVisitor $visitor,
    ) {
    }

    public function fetch(mixed $subject): Number
    {
        return $this->handler->handle(
            visitor: $this->visitor,
            deferredResolver: new DeferredResolver($subject),
        );
    }
}
