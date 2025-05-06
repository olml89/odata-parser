<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\Geolocation;

final readonly class GeolocationScope implements Scope
{
    use IsScope;

    public function __construct(
        private Geolocation $geolocation,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new GeolocationScopeFactory();
    }

    /**
     * @throws ValueTypeException
     */
    protected function resolveProperty(Property $property): Resolved
    {
        return match ($property->name->value()) {
            'latitude' => FloatValue::from($this->geolocation->latitude->value),
            'longitude' => FloatValue::from($this->geolocation->longitude->value),
            default => new NullValue(),
        };
    }
}
