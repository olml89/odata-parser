<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;

interface Scope
{
    /**
     * @throws ValueTypeException
     */
    public function resolve(?Property $property): Resolved;
}
