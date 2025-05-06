<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\District;

final readonly class DistrictScope implements Scope
{
    use IsScope;

    public function __construct(
        private District $district,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new DistrictScopeFactory();
    }

    /**
     * @throws ValueTypeException
     */
    protected function resolveProperty(Property $property): Resolved
    {
        return match ($property->name->value()) {
            'name' => StringValue::from($this->district->name->value),
            'neighborhoods' => new ScopedCollection(
                NeighborhoodScope::factory(),
                $this->district->neighborhoods,
            ),
            default => new NullValue(),
        };
    }
}
