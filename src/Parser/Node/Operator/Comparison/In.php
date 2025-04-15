<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Operator\Comparison;

use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Property;

final readonly class In implements Node
{
    public Property $property;

    /**
     * @var Literal[]
     */
    public array $values;

    public function __construct(Property $property, Literal ...$values)
    {
        $this->property = $property;
        $this->values = $values;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s (%s)',
            $this->property,
            ComparisonOperator::in->value,
            implode(
                ', ',
                array_map(
                    fn (Literal $value): string => (string)$value,
                    $this->values,
                ),
            ),
        );
    }
}
