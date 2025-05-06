<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope\Deferred;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\SemanticAnalyzer\Exception\UnknownPropertyException;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeManager;

final readonly class DeferredResolved implements Deferred
{
    public function __construct(
        private Property $property,
        private ScopeManager $scopeManager,
    ) {
    }

    /**
     * @throws ValueTypeException
     * @throws UnknownPropertyException
     */
    public function fetch(mixed $subject): Resolved
    {
        return $this->scopeManager->resolve($subject, $this->property);
    }
}
