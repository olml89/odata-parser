<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use Stringable;

interface Value extends Resolved, Stringable
{
    public static function type(): ValueType;
    public function eq(Value $value): BoolValue;
    public function ne(Value $value): BoolValue;

    /**
     * @return null|bool|int|float|string|Scalar[]
     */
    public function value(): null|bool|int|float|string|array;
}
