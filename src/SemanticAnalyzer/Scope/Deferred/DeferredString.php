<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\StringHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

final readonly class DeferredString implements Deferred
{
    public function __construct(
        private StringHandler $handler,
        private PredicateBuilderVisitor $visitor,
    ) {
    }

    public function fetch(mixed $subject): StringValue
    {
        return $this->handler->handle(
            visitor: $this->visitor,
            deferredResolver: new DeferredResolver($subject),
        );
    }
}
