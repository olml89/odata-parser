<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\SemanticAnalyzer\Exception\UnknownPropertyException;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;

final class ScopeManager
{
    private ?RootScopeContext $rootScopeContext = null;

    /**
     * @var LocalScopeContext[]
     */
    private array $localScopeContextsStack = [];

    public function __construct(
        private readonly ScopeFactory $rootScopeFactory,
    ) {
    }

    private function push(LocalScopeContext $localScopeContext): void
    {
        array_unshift($this->localScopeContextsStack, $localScopeContext);
    }

    private function pop(): void
    {
        array_shift($this->localScopeContextsStack);
    }

    /**
     * This will be called from the PredicateBuilderVisitor at demand, from the ScopedCollection any or all methods.
     */
    public function executeInScope(LocalScopeContext $localScopeContext, DeferredPredicate $predicate): BoolValue
    {
        $this->push($localScopeContext);
        $value = $predicate->fetch($localScopeContext->subject);
        $this->pop();

        return $value;
    }

    private function rootScopeContext(mixed $subject): RootScopeContext
    {
        if (is_null($this->rootScopeContext) || count($this->localScopeContextsStack) === 0) {
            $this->rootScopeContext = new RootScopeContext($subject, $this->rootScopeFactory);
        }

        return $this->rootScopeContext;
    }

    private function isNotNullOrIsNullable(Resolved $resolved): bool
    {
        return !($resolved instanceof NullValue) || $resolved->nullable();
    }

    /**
     * This will be called from the PredicateBuilderVisitor when visiting a Property.
     *
     * @throws UnknownPropertyException
     * @throws ValueTypeException
     */
    public function resolve(mixed $subject, Property $property): Resolved
    {
        foreach ($this->localScopeContextsStack as $localScopeContext) {
            $resolved = $localScopeContext->resolve($subject, $property);

            if ($this->isNotNullOrIsNullable($resolved)) {
                return $resolved;
            }
        }

        /**
         * RootScopeContext acts as a fallback.
         * It doesn't check types: if a property is on scope and can be resolved, it does it.
         */
        $resolved = $this->rootScopeContext($subject)->resolve($property);

        if ($this->isNotNullOrIsNullable($resolved)) {
            return $resolved;
        }

        throw new UnknownPropertyException($subject, $property);
    }
}
