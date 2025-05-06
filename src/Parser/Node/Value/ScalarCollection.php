<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

final readonly class ScalarCollection implements Value
{
    use IsValue;

    /**
     * @var Scalar[]
     */
    public array $values;

    public static function type(): ValueType
    {
        return ValueType::ScalarCollection;
    }

    public function __construct(Scalar ...$values)
    {
        $this->values = $values;
    }

    public function contains(Scalar $value): BoolValue
    {
        $found = array_any(
            $this->values,
            fn (Scalar $collectionValue): bool => $collectionValue->eq($value)->value(),
        );

        return new BoolValue($found);
    }

    /**
     * @return Scalar[]
     */
    public function value(): array
    {
        return $this->values;
    }

    /**
     * @return Scalar[]
     */
    public function array(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s]',
            implode(
                ', ',
                array_map(
                    fn (Scalar $value): string => (string)$value,
                    $this->values,
                ),
            ),
        );
    }
}
