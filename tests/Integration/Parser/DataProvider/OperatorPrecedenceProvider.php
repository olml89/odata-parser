<?php

declare(strict_types=1);

namespace Tests\Integration\Parser\DataProvider;

final readonly class OperatorPrecedenceProvider
{
    /**
     * @return array<string, list<string>>
     */
    public static function provide(): array
    {
        return [
            /**
             * This dataProvider will verify the correct formation of the AST checking that the parentheses when
             * serializing it are correctly put, instead of directly checking the tree structure.
             */
            /*
            'simple comparison' => [
                'name eq \'John Smith\'',
                'name eq \'John Smith\'',
            ],
            'numeric comparison' => [
                'age ge 18',
                'age ge 18',
            ],
            'age comparison and function call' => [
                'age ge 18 and startswith(name, \'J\')',
                '(age ge 18) and (startswith(name, \'J\'))',
            ],
            'nested properties' => [
                'orders/items/invoice/amount gt 100',
                'orders/items/invoice/amount gt 100',
            ],
            'nested functions as arguments for other functions' => [
                'endswith(tolower(name), \'smith\')',
                'endswith(tolower(name), \'smith\')',
            ],
            'complex expression with parentheses' => [
                '(startswith(name, \'J\') or age lt 30) and active eq true',
                '((startswith(name, \'J\')) or (age lt 30)) and (active eq true)',
            ],
            'nested logical expressions with functions' => [
                '(startswith(Name, \'J\') and age gt 18) or not endswith(email, \'.com\')',
                '((startswith(Name, \'J\')) and (age gt 18)) or (not (endswith(email, \'.com\')))',
            ],
            'parentheses are added taken into account operator precedence when there is ambiguity' => [
                'startswith(Name, \'J\') and age gt 18 or not endswith(email, \'.com\')',
                '((startswith(Name, \'J\')) and (age gt 18)) or (not (endswith(email, \'.com\')))',
            ],
            'redundant parentheses are removed when not needed' => [
                '((startswith(Name, \'J\') and (age gt 18)) or (not (endswith(email, \'.com\')))',
                '(startswith(Name, \'J\') and (age gt 18)) or (not endswith(email, \'.com\'))',
            ],
            'and precedence over or when no parentheses are specified' => [
                'a eq 1 or b eq 2 and c eq 3',
                '(a eq 1) or ((b eq 2) and (c eq 3))'
            ],
            'or precedence over and when parentheses are specified' => [
                '(a eq 1 or b eq 2) and c eq 3',
                '((a eq 1) or (b eq 2)) and (c eq 3)'
            ],
            'nested subexpressions at the beginning of an or the expression' => [
                '(a eq 2 and b eq 3 or c lt 3) or c eq 5',
                '(((a eq 2) and (b eq 3)) or (c lt 3)) or (c eq 5)',
            ],
            'nested subexpressions at the beginning of an and the expression' => [
                '(a eq 2 and b eq 3 or c lt 3) and c eq 5',
                '(((a eq 2) and (b eq 3)) or (c lt 3)) and (c eq 5)',
            ],
            'nested logical clauses with redundant parentheses' => [
                '((((a eq 1))) or ((b eq 2) and (c ne 3)))',
                '(a eq 1) or ((b eq 2) and (c ne 3))',
            ],
            'not over parentheses and combined expressions' => [
                'not ((a gt 10 or b lt 5) and c eq 3)',
                'not (((a gt 10) or (b lt 5)) and (c eq 3))',
            ],
            'expression with nested arithmetical and logical subexpressions, redundant parentheses are removed' => [
                '((a add 2) mul (b sub 3)) lt 100 and not (c eq null)',
                '((a add 2) mul (b sub 3) lt 100) and (not (c eq null))',
            ],
            'in expression' => [
                'category in (\'books\', \'movies\') or price gt 20',
                '(category in (\'books\', \'movies\')) or (price gt 20)',
            ],
            'has expression with a literal' => [
                'flags has \'important\'',
                'flags has \'important\'',
            ],
            'any accessing entities' => [
                'status/any(s: s in (\'processing\', \'accepted\'))',
                'status/any(s: s in (\'processing\', \'accepted\'))',
            ],
            'any accessing entity properties' => [
                'orders/any(o: o/amount gt 100 and o/status eq \'open\')',
                'orders/any(o: (o/amount gt 100) and (o/status eq \'open\'))',
            ],
            'any accessing nested entity properties' => [
                'order/items/any(i: i/invoice/amount gt 100 and i/status eq \'open\')',
                'order/items/any(i: (i/invoice/amount gt 100) and (i/status eq \'open\'))',
            ],
            'all accessing entities' => [
                'status/all(s: s in (\'processing\', \'accepted\'))',
                'status/all(s: s in (\'processing\', \'accepted\'))',
            ],
            'all accessing entity properties' => [
                'orders/all(o: o/amount gt 100 and o/status eq \'open\')',
                'orders/all(o: (o/amount gt 100) and (o/status eq \'open\'))',
            ],
            'all accessing nested entity properties' => [
                'order/items/all(i: i/invoice/amount gt 100 and i/status eq \'open\')',
                'order/items/all(i: (i/invoice/amount gt 100) and (i/status eq \'open\'))',
            ],
            'combination of functions, parentheses, not, or, and: and takes precedence, parentheses added' => [
                'not startswith(name, \'J\') or endswith(name, \'Smith\') and age ge 18',
                '(not startswith(name, \'J\')) or (endswith(name, \'Smith\') and (age ge 18))',
            ],
            */
            'combination of functions, parentheses, not, and, or: and takes precedence, parentheses added' => [
                'not startswith(name, \'J\') and endswith(name, \'Smith\') or age ge 18',
                '((not startswith(name, \'J\')) and endswith(name, \'Smith\')) or (age ge 18)',
            ],
            'expression with nested arithmetical subexpressions' => [
                '(a add b) mul (c div d) sub (e mod f)',
                '((a add b) mul (c div d)) sub (e mod f)',
            ],
        ];
    }
}
