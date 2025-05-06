<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node\DataProvider;

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
use olml89\ODataParser\Parser\Node\Node;
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

final readonly class NodeAndVisitorMethodProvider extends NodeProvider
{
    /**
     * @return array<string, array{0: Node, 1: non-empty-string}>
     */
    public static function provide(): array
    {
        return [
            /**
             * Functions
             */
            'concat' => [
                new Concat(self::property(), self::literal()),
                'visitConcat',
            ],
            'contains' => [
                new Contains(self::property(), self::literal()),
                'visitContains',
            ],
            'endswith' => [
                new EndsWith(self::property(), self::literal()),
                'visitEndsWith',
            ],
            'indexof' => [
                new IndexOf(self::property(), self::literal()),
                'visitIndexOf',
            ],
            'length' => [
                new Length(self::property()),
                'visitLength',
            ],
            'matchesPattern' => [
                new MatchesPattern(self::property(), self::literal()),
                'visitMatchesPattern',
            ],
            'startswith' => [
                new StartsWith(self::property(), self::literal()),
                'visitStartsWith',
            ],
            'substring' => [
                new Substring(self::property(), self::literal(), self::literal()),
                'visitSubstring',
            ],
            'tolower' => [
                new Tolower(self::property()),
                'visitToLower',
            ],
            'toupper' => [
                new Toupper(self::property()),
                'visitToUpper',
            ],
            'trim' => [
                new Trim(self::property()),
                'visitTrim',
            ],

            /**
             * Arithmetic expressions
             */
            'add' => [
                new Add(self::expression(), self::expression()),
                'visitAdd',
            ],
            'div' => [
                new Div(self::expression(), self::expression()),
                'visitDiv',
            ],
            'divby' => [
                new DivBy(self::expression(), self::expression()),
                'visitDivBy',
            ],
            'minus' => [
                new Minus(self::expression()),
                'visitMinus',
            ],
            'mod' => [
                new Mod(self::expression(), self::expression()),
                'visitMod',
            ],
            'mul' => [
                new Mul(self::expression(), self::expression()),
                'visitMul',
            ],
            'sub' => [
                new Sub(self::expression(), self::expression()),
                'visitSub',
            ],

            /**
             * Comparison expressions
             */
            'all' => [
                new All(self::property(), self::property(), self::expression()),
                'visitAll',
            ],
            'any' => [
                new Any(self::property(), self::property(), self::expression()),
                'visitAny',
            ],
            'equal' => [
                new Equal(self::expression(), self::expression()),
                'visitEqual',
            ],
            'greater than' => [
                new GreaterThan(self::expression(), self::expression()),
                'visitGreaterThan',
            ],
            'greater than or equal' => [
                new GreaterThanOrEqual(self::expression(), self::expression()),
                'visitGreaterThanOrEqual',
            ],
            'has' => [
                new Has(self::expression(), self::expression()),
                'visitHas',
            ],
            'in' => [
                new In(self::property(), self::literal(), self::literal()),
                'visitIn',
            ],
            'less than' => [
                new LessThan(self::expression(), self::expression()),
                'visitLessThan',
            ],
            'less than or equal' => [
                new LessThanOrEqual(self::expression(), self::expression()),
                'visitLessThanOrEqual',
            ],
            'not equal' => [
                new NotEqual(self::expression(), self::expression()),
                'visitNotEqual',
            ],

            /**
             * Logical expressions
             */
            'not' => [
                new NotExpression(self::expression()),
                'visitNot',
            ],
            'and' => [
                new AndExpression(self::expression(), self::expression()),
                'visitAnd',
            ],
            'or' => [
                new OrExpression(self::expression(), self::expression()),
                'visitOr',
            ],

            /**
             * Property
             */
            'property' => [
                self::property(),
                'visitProperty',
            ],

            /**
             * Literal
             */
            'literal' => [
                self::literal(),
                'visitLiteral',
            ],
        ];
    }
}
