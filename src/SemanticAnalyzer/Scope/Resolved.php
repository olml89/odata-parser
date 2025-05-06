<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Value\ValueType;

/**
 * Common abstraction for data objects than can be resolved from a scope: Value, ScopedCollection
 */
interface Resolved
{
    public static function type(): ResolvedType|ValueType;
}
