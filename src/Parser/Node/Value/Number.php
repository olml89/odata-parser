<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

interface Number extends Scalar
{
    public function add(Number $number): Number;
    public function sub(Number $number): Number;
    public function mul(Number $number): Number;
    public function div(Number $number): Number;
    public function divBy(Number $number): Number;
    public function mod(Number $number): Number;
    public function minus(): Number;
    public function ge(Number $number): BoolValue;
    public function gt(Number $number): BoolValue;
    public function le(Number $number): BoolValue;
    public function lt(Number $number): BoolValue;
    public function normalize(): Number;
    public function round(int $precision = 2): Number;
    public function value(): int|float;
}
