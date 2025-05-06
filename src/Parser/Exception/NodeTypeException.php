<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;

final class NodeTypeException extends ParserException
{
    public function __construct(?Node $node, NodeType ...$expectedTypes)
    {
        parent::__construct(
            sprintf(
                'Node of type %s expected, got %s',
                implode(
                    ' or ',
                    array_map(
                        fn (NodeType $expectedType): string => $expectedType->value,
                        $expectedTypes,
                    ),
                ),
                is_null($node) ? 'null' : sprintf('node with type %s', $node->type->value),
            ),
        );
    }
}
