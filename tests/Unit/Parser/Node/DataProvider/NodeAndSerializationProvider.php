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

final readonly class NodeAndSerializationProvider extends NodeProvider
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
                'concat(property, \'literal\')',
            ],
            'contains' => [
                new Contains(self::property(), self::literal()),
                'contains(property, \'literal\')',
            ],
            'endswith' => [
                new EndsWith(self::property(), self::literal()),
                'endswith(property, \'literal\')',
            ],
            'indexof' => [
                new IndexOf(self::property(), self::literal()),
                'indexof(property, \'literal\')',
            ],
            'length' => [
                new Length(self::property()),
                'length(property)',
            ],
            'matchesPattern' => [
                new MatchesPattern(self::property(), self::literal()),
                'matchesPattern(property, \'literal\')',
            ],
            'startswith' => [
                new StartsWith(self::property(), self::literal()),
                'startswith(property, \'literal\')',
            ],
            'substring' => [
                new Substring(self::property(), self::literal(), self::literal()),
                'substring(property, \'literal\', \'literal\')',
            ],
            'tolower' => [
                new Tolower(self::property()),
                'tolower(property)',
            ],
            'toupper' => [
                new Toupper(self::property()),
                'toupper(property)',
            ],
            'trim' => [
                new Trim(self::property()),
                'trim(property)',
            ],

            /**
             * Arithmetic expressions
             */
            'add' => [
                new Add(self::expression(), self::expression()),
                '(expression) add (expression)',
            ],
            'div' => [
                new Div(self::expression(), self::expression()),
                '(expression) div (expression)',
            ],
            'divby' => [
                new DivBy(self::expression(), self::expression()),
                '(expression) divby (expression)',
            ],
            'minus' => [
                new Minus(self::expression()),
                '- (expression)',
            ],
            'mod' => [
                new Mod(self::expression(), self::expression()),
                '(expression) mod (expression)',
            ],
            'mul' => [
                new Mul(self::expression(), self::expression()),
                '(expression) mul (expression)',
            ],
            'sub' => [
                new Sub(self::expression(), self::expression()),
                '(expression) sub (expression)',
            ],

            /**
             * Comparison expressions
             */
            'all' => [
                new All(self::property(), self::property(), self::expression()),
                'property/all(property: expression)',
            ],
            'any' => [
                new Any(self::property(), self::property(), self::expression()),
                'property/any(property: expression)',
            ],
            'equal' => [
                new Equal(self::expression(), self::expression()),
                '(expression) eq (expression)',
            ],
            'greater than' => [
                new GreaterThan(self::expression(), self::expression()),
                '(expression) gt (expression)',
            ],
            'greater than or equal' => [
                new GreaterThanOrEqual(self::expression(), self::expression()),
                '(expression) ge (expression)',
            ],
            'has' => [
                new Has(self::expression(), self::expression()),
                '(expression) has (expression)',
            ],
            'in' => [
                new In(self::property(), self::literal(), self::literal()),
                'property in (\'literal\', \'literal\')',
            ],
            'less than' => [
                new LessThan(self::expression(), self::expression()),
                '(expression) lt (expression)',
            ],
            'less than or equal' => [
                new LessThanOrEqual(self::expression(), self::expression()),
                '(expression) le (expression)',
            ],
            'not equal' => [
                new NotEqual(self::expression(), self::expression()),
                '(expression) ne (expression)',
            ],

            /**
             * Logical expressions
             */
            'not' => [
                new NotExpression(self::expression()),
                'not (expression)',
            ],
            'and' => [
                new AndExpression(self::expression(), self::expression()),
                '(expression) and (expression)',
            ],
            'or' => [
                new OrExpression(self::expression(), self::expression()),
                '(expression) or (expression)',
            ],

            /**
             * Property
             */
            'property' => [
                self::property(),
                'property',
            ],

            /**
             * Literal
             */
            'literal' => [
                self::literal(),
                '\'literal\'',
            ],
        ];
    }
}
