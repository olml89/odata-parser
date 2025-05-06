<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Exception;

use olml89\ODataParser\Parser\Node\Node;

final class InvalidAstException extends SemanticException
{
    public function __construct(Node $node)
    {
        parent::__construct(
            sprintf(
                'Invalid AST: %s',
                $node,
            ),
        );
    }
}
