<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use Tests\Helper\City\Entity\Neighborhood;

final readonly class NeighborhoodScope implements Scope
{
    use IsScope;

    public function __construct(
        private Neighborhood $neighborhood,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new NeighborhoodScopeFactory();
    }

    /**
     * @throws ValueTypeException
     */
    protected function resolveProperty(Property $property): Resolved
    {
        return match ($property->name->value()) {
            'name' => StringValue::from($this->neighborhood->name->value),
            'registeredSchoolsCount' => IntValue::from($this->neighborhood->registeredSchoolsCount),
            default => new NullValue(),
        };
    }
}
