<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Exception;

use olml89\ODataParser\Parser\Node\Property;

final class UnknownPropertyException extends SemanticException
{
    public function __construct(mixed $subject, Property $property)
    {
        parent::__construct(
            sprintf(
                'Unknown property \'%s\' in %s',
                $property,
                is_object($subject) ? get_class($subject) : gettype($subject),
            ),
        );
    }
}
