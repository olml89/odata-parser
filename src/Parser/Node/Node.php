<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node;

use olml89\ODataParser\SemanticAnalyzer\Visitor;
use Stringable;

interface Node extends Stringable
{
    public NodeType $type { get; }

    public function accept(Visitor $visitor): mixed;
}
