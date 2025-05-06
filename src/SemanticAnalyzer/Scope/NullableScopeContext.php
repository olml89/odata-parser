<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;

final readonly class NullableScopeContext
{
    public function __construct(
        private ScopeFactory $scopeFactory,
    ) {
    }

    /**
     * @throws ValueTypeException
     */
    public function resolve(mixed $subject, Property $property): Resolved
    {
        if (is_null($subject)) {
            return new NullValue(nullable: true);
        }

        $scope = $this->scopeFactory->create($subject);

        if (is_null($scope)) {
            return new NullValue();
        }

        return $scope->resolve($property);
    }
}
