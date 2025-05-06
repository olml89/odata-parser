<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;

final readonly class TagScope implements Scope
{
    public function __construct(
        private string $tag,
    ) {
    }

    public static function factory(): ScopeFactory
    {
        return new TagScopeFactory();
    }

    public function resolve(?Property $property): StringValue
    {
        return new StringValue($this->tag);
    }
}
