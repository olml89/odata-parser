<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer;

use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\ToUpper;
use olml89\ODataParser\Parser\Node\Function\Trim;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Expression\Comparison\All;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Has;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Property;

interface Visitor
{
    /**
     * Functions
     */
    public function visitConcat(Concat $concat): mixed;
    public function visitContains(Contains $contains): mixed;
    public function visitEndsWith(EndsWith $endsWith): mixed;
    public function visitIndexOf(IndexOf $indexOf): mixed;
    public function visitLength(Length $length): mixed;
    public function visitMatchesPattern(MatchesPattern $matchesPattern): mixed;
    public function visitStartsWith(StartsWith $startsWith): mixed;
    public function visitSubstring(Substring $substring): mixed;
    public function visitToLower(ToLower $toLower): mixed;
    public function visitToUpper(ToUpper $toUpper): mixed;
    public function visitTrim(Trim $trim): mixed;

    /**
     * Arithmetic expressions
     */
    public function visitAdd(Add $add): mixed;
    public function visitDiv(Div $div): mixed;
    public function visitDivBy(DivBy $divBy): mixed;
    public function visitMinus(Minus $minus): mixed;
    public function visitMod(Mod $mod): mixed;
    public function visitMul(Mul $mul): mixed;
    public function visitSub(Sub $sub): mixed;

    /**
     * Comparison expressions
     */
    public function visitAll(All $all): mixed;
    public function visitAny(Any $any): mixed;
    public function visitEqual(Equal $equal): mixed;
    public function visitGreaterThan(GreaterThan $greaterThan): mixed;
    public function visitGreaterThanOrEqual(GreaterThanOrEqual $greaterThanOrEqual): mixed;
    public function visitHas(Has $has): mixed;
    public function visitIn(In $in): mixed;
    public function visitLessThan(LessThan $lessThan): mixed;
    public function visitLessThanOrEqual(LessThanOrEqual $lessThanOrEqual): mixed;
    public function visitNotEqual(NotEqual $notEqual): mixed;

    /**
     * Logical expressions
     */
    public function visitAnd(AndExpression $andExpression): mixed;
    public function visitNot(NotExpression $notExpression): mixed;
    public function visitOr(OrExpression $orExpression): mixed;

    /**
     * Literals
     */
    public function visitLiteral(Literal $literal): mixed;

    /**
     * Properties
     */
    public function visitProperty(Property $property): mixed;
}
