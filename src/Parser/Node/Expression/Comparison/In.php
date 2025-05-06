<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Expression\Comparison;

use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\Keyword;
use olml89\ODataParser\Parser\Node\Expression\Expression;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Expression\IsExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\SemanticAnalyzer\Visitor;

final class In implements Expression
{
    use IsExpression;

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

    protected function keyword(): Keyword
    {
        return ComparisonOperator::in;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->visitIn($this);
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
