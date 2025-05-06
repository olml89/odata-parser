<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\City;

final readonly class CityScope implements Scope
{
    use IsScope;

    public function __construct(
        private City $city,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new CityScopeFactory();
    }

    /**
     * @throws ValueTypeException
     */
    protected function resolveProperty(Property $property): Resolved
    {
        return match ($property->name->value()) {
            'name' => StringValue::from($this->city->name->value),
            'geolocation' => new GeolocationScope($this->city->geolocation)->resolve($property->subProperty),
            'population' => IntValue::from($this->city->population->value),
            'governedBy' => StringValue::from($this->city->governedBy->value),
            'tags' => new ScopedCollection(
                TagScope::factory(),
                $this->city->tags,
            ),
            'districts' => new ScopedCollection(
                DistrictScope::factory(),
                $this->city->districts,
            ),
            default => new NullValue(),
        };
    }
}
