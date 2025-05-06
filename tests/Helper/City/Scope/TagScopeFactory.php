<?php

declare(strict_types=1);

namespace Tests\Helper\City\Scope;

use olml89\ODataParser\SemanticAnalyzer\Scope\Scope;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;

final class TagScopeFactory implements ScopeFactory
{
    public function create(mixed $subject): ?Scope
    {
        return is_string($subject) ? new TagScope($subject) : null;
    }
}
