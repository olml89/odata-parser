<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;

final readonly class LocalScopeContext
{
    public function __construct(
        public mixed $subject,
        private Property $variable,
        private ScopeFactory $scopeFactory,
    ) {
    }

    private function subjectMatchesScopeSubjectType(mixed $subject): bool
    {
        if (is_object($subject)) {
            return is_object($this->subject) && $subject::class === $this->subject::class;
        }

        return !is_object($this->subject) && gettype($subject) === gettype($this->subject);
    }

    private function propertyMatchesVariable(Property $property): bool
    {
        return $this->variable->name->eq($property->name)->bool();
    }

    /**
     * @throws ValueTypeException
     */
    public function resolve(mixed $subject, Property $property): Resolved
    {
        if (!$this->subjectMatchesScopeSubjectType($subject) || !$this->propertyMatchesVariable($property)) {
            return new NullValue();
        }

        $scope = $this->scopeFactory->create($this->subject);

        if (is_null($scope)) {
            return new NullValue();
        }

        /**
         * Local scoped lambda with the possible forms:
         * t: t eq 'y'
         * t: t/x eq 'y'
         *
         * 1) We check in LocalScopeContext that the property (t, t/ex) refers to the t scope
         *
         * 2) If the property is in scope, we have to continue checking either with the property (t),
         * or the sub-property if the property was only used as a reference for the scope (t/x):
         */
        return $scope->resolve($property->subProperty ?? $property);
    }
}
