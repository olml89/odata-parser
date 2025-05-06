<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;

/**
 * @mixin Scope
 */
trait IsScope
{
    abstract public static function factory(): ScopeFactory;
    abstract protected function resolveProperty(Property $property): Resolved;

    /**
     * @throws ValueTypeException
     */
    public function resolve(?Property $property): Resolved
    {
        if (is_null($property)) {
            return new NullValue();
        }

        return $this->resolveProperty($property);
    }
}
