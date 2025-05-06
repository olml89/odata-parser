<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;

final readonly class CScope implements Scope
{
    use IsScope;

    public function __construct(
        private C $c,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new CScopeFactory();
    }

    public function resolveProperty(Property $property): Resolved
    {
        return match ($property->name->value()) {
            'c' => new StringValue($this->c->c),
            default => new NullValue(),
        };
    }
}
