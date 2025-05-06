<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Handler;

use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;

interface StringHandler extends Handler
{
    public function handle(PredicateBuilderVisitor $visitor, DeferredResolver $deferredResolver): StringValue;
}
