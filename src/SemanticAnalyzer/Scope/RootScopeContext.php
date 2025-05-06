<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;

final readonly class RootScopeContext
{
    public function __construct(
        public mixed $subject,
        private ScopeFactory $scopeFactory,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function resolve(Property $property): Resolved
    {
        $scope = $this->scopeFactory->create($this->subject);

        if (is_null($scope)) {
            return new NullValue();
        }

        return $scope->resolve($property);
    }
}
