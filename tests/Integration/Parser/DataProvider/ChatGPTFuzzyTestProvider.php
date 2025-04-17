<?php

declare(strict_types=1);

namespace Tests\Integration\Parser\DataProvider;

final readonly class ChatGPTFuzzyTestProvider
{
    /**
     * @return list<list<string>>
     */
    public static function provide(): array
    {
        return [
            /**
             * 🧪 Simple comparisons
             */
            ['age eq 18'],
            ['price gt 100.5'],
            ['name ne \'John\''],
            ['active eq true'],
            ['deleted eq false'],
            ['score lt 50'],
            ['score le 50'],
            ['score ge 90'],
            ['rating eq null'],

            /**
             * 🪞 Reflexive comparisons
             */
            ['score eq score'],
            ['not (price ne price)'],

            /**
             * 🧪 Redundant boolean comparisons
             */
            [
                'active eq true and active',
                '(active eq true) and active',
            ],
            [
                'not active eq false',
                'not (active eq false)',
            ],

            /**
             * 🔀 Logical operators
             */
            [
                'age gt 18 and age lt 30',
                '(age gt 18) and (age lt 30)',
            ],
            [
                'status eq \'active\' or status eq \'pending\'',
                '(status eq \'active\') or (status eq \'pending\')',
            ],
            ['not (status eq \'inactive\')'],

            /**
             * 🔍 'not' combined with 'in'
             */
            [
                'not (status in (\'inactive\', \'banned\'))',
                'not status in (\'inactive\', \'banned\')'
            ],

            /**
             * 🧼 'not' combined with boolean functions
             */
            ['not contains(name, \'X\')'],

            /**
             * 🔤 Functions
             */
            ['contains(name, \'abc\')'],
            ['startswith(name, \'A\')'],
            ['endswith(email, \'.com\')'],
            ['indexof(description, \'test\') gt 0'],
            ['length(username) gt 5'],
            ['matchesPattern(name, \'^J.*n$\')'],
            ['substring(title, 2, 4) eq \'lo\''],
            ['tolower(Country) eq \'spain\''],
            ['toupper(code) eq \'ABC123\''],
            ['trim(name) eq \'John\''],

            /**
             * 🧾 Nested functions in cascade
             */
            ['toupper(trim(name)) eq \'JOHN DOE\''],
            ['substring(tolower(email), 0, 5) eq \'admin\''],
            ['contains(substring(name, 0, 3), \'Joh\')'],

            /**
             * 📬 Multiple nested functions with arithmetics
             */
            [
                'indexof(trim(subject), \'urgent\') add 1 gt 0',
                '(indexof(trim(subject), \'urgent\') add 1) gt 0',
            ],

            /**
             * ⚡ Boolean function directly combined with 'and'
             */
            ['contains(name, \'A\') and startswith(name, \'B\')'],

            /**
             * 🔗 Comparison with boolean functions
             */
            ['contains(name, \'a\') eq true'],

            /**
             * 🧵 Comparison with function returning a number
             */
            ['length(title) eq 10'],

            /**
             * 🔠 Case sensitivity quirks
             */
            [
                'ToLower(Name) eq \'john\'',
                'tolower(Name) eq \'john\'',
            ],
            [
                'ENDSwith(email, \'.com\')',
                'endswith(email, \'.com\')',
            ],

            /**
             * 🧿 Properties named as strings
             */
            ['contains eq true'],
            ['startswith eq \'yes\''],

            /**
             * ➕ Arithmetic operators
             */
            [
                'total add 5 eq 100',
                '(total add 5) eq 100',
            ],
            [
                'price sub 10 lt 90',
                '(price sub 10) lt 90',
            ],
            [
                'quantity mul 2 eq 6',
                '(quantity mul 2) eq 6',
            ],
            [
                'price div 2 eq 50',
                '(price div 2) eq 50',
            ],
            [
                'price divby 5 eq 10',
                '(price divby 5) eq 10',
            ],
            [
                'price mod 2 eq 0',
                '(price mod 2) eq 0',
            ],

            /**
             * 🧮 Aritmethics with malicious parentheses
             */
            [
                '(((total add 5) mul 2) mod 3) divby 2 eq 1',
                '((((total add 5) mul 2) mod 3) divby 2) eq 1',
            ],

            /**
             * 🧮 Arithmetic over functions
             */
            [
                'length(name) add 5 gt 10',
                '(length(name) add 5) gt 10'
            ],

            /**
             * 🧨 'mod' and 'divby' combined
             */
            ['((count mod 5) divby 2) eq 1'],

            /**
             * ⚖️ Comparisons combined with arithmetic operators
             */
            [
                'price add tax sub discount eq total',
                '((price add tax) sub discount) eq total',
            ],
            [
                'amount mul quantity div rate lt 1000',
                '((amount mul quantity) div rate) lt 1000',
            ],
            ['((price add 10) mod 4) eq 2'],

            /**
             * 🔁 Nested properties
             */
            ['order/items/invoice/amount gt 100'],
            ['user/profile/address/city eq \'Madrid\''],
            ['a/b/c/d/e/f/g/h/i/j/k eq \'nested\''],

            /**
             * 🔃 Double use of properties in an expression
             */
            [
                'price add price gt 100',
                '(price add price) gt 100',
            ],

            /**
             * 🧬 Lambda expressions
             */
            ['items/any(i: i/price gt 50)'],
            ['items/all(i: i/available eq true)'],
            ['orders/any(o: o/items/any(i: i/price gt 10))'],
            ['orders/all(o: o/status eq \'shipped\')'],
            ['products/any(p: startswith(p/name, \'S\'))'],
            ['categories/all(c: contains(c/name, \'Electronics\'))'],

            /**
             * 🧊 Lambda expressions with literal boolean value as a predicate
             */
            ['items/any(i: true)'],

            /**
             * 🪄 Lambda expression with long variables
             */
            ['items/any(item: item/product/description eq \'abc\')'],

            /**
             * 🌀 Lambda expressions with multiple nested levels
             */
            ['departments/all(d: d/employees/all(e: e/skills/any(s: startswith(s, \'Java\'))))'],
            ['clients/any(c: c/contracts/all(ct: ct/payments/any(p: p/amount gt 100)))'],

            /**
             * 🌀 Lambda expressions referencing outside context
             */
            ['items/any(i: i/price gt total)'],

            /**
             * 🪄 Lambda expressions containing functions
             */
            ['items/any(i: contains(i/description, \'discount\'))'],
            ['users/any(u: length(u/username) gt 8)'],

            /**
             * 🗜️ Lambda expression over a no collection-like property
             */
            ['status/any(s: s eq \'done\')'],

            /**
             * 🧩 'any' with a complex predicate
             */
            [
                'comments/any(c: contains(c/content, \'error\') and c/author eq \'admin\')',
                'comments/any(c: contains(c/content, \'error\') and (c/author eq \'admin\'))',
            ],

            /**
             * 🧱 Combination with 'all' and 'not'
             */
            ['orders/all(o: not (o/status eq \'cancelled\'))'],

            /**
             * 🧩 Complex subexpressions
             */
            [
                '(price gt 100 or price lt 10) and available eq true',
                '((price gt 100) or (price lt 10)) and (available eq true)',
            ],
            [
                'not (quantity lt 5 or quantity gt 100)',
                'not ((quantity lt 5) or (quantity gt 100))',
            ],
            ['((score add 5) gt 100) or (status eq \'active\')'],

            /**
             * 📦 'in' operator
             */
            ['status in (\'active\', \'pending\', \'archived\')'],
            ['category in (\'books\', \'games\')'],

            /**
             * 🐚 'in' operator with numbers
             */
            ['score in (1, 2, 3, 4, 5)'],

            /**
             * 📜 'in' operator with mixed types
             */
            ['status in (\'active\', 123, true, null)'],

            /**
             * ⚙️ 'has' operator
             */
            ['tags has \'featured\''],
            ['roles has \'admin\''],

            /**
             * 🔗 'has' operator used with booleans
             */
            ['active has true'],

            /**
             * 🔀 'in' and 'has' operators combined
             */
            [
                'roles has \'admin\' and status in (\'active\', \'pending\')',
                '(roles has \'admin\') and (status in (\'active\', \'pending\'))',
            ],

            /**
             * 🌳 Nested lambda functions
             */
            ['items/any(i: i/subitems/any(s: s/value eq 42))'],
            ['products/any(p: p/features/all(f: f/enabled eq true))'],

            /**
             * 🧠 Extreme complex expressions
             */
            [
                'users/any(u: u/roles/all(r: r/name eq \'admin\' or r/level gt 3)) and department eq \'IT\'',
                '(users/any(u: u/roles/all(r: (r/name eq \'admin\') or (r/level gt 3)))) and (department eq \'IT\')',
            ],
            [
                'data/any(d: (d/age gt 30 and d/location eq \'US\') or (d/age lt 20 and d/location eq \'EU\'))',
                'data/any(d: ((d/age gt 30) and (d/location eq \'US\')) or ((d/age lt 20) and (d/location eq \'EU\')))',
            ],
            [
                'clients/all(c: c/projects/any(p: p/budget gt 100000 and p/status ne \'cancelled\'))',
                'clients/all(c: c/projects/any(p: (p/budget gt 100000) and (p/status ne \'cancelled\')))',
            ],

            /**
             * 🧪 Special types
             */
            ['birthdate eq null'],
            ['isActive eq true'],
            ['deleted eq false'],

            /**
             * 🎭 Mixed boolean expressions
             */
            [
                '(flag eq true or flag eq false) and flag ne null',
                '((flag eq true) or (flag eq false)) and (flag ne null)',
            ],

            /**
             * 🧷 Comparisons with strings that seem special types
             */
            ['birthdate eq \'null\''],
            ['flag eq \'true\''],
            ['deleted eq \'false\''],

            /**
             * 🔣 Usage of reserved symbols as strings
             */
            ['description eq \'and or eq ne gt lt\''],

            /**
             * 📅 Dates represented as strings
             */
            ['createdAt eq \'2023-12-01T00:00:00Z\''],

            /**
             * 🛠️ Combined and nested operators
             */
            [
                '((price add 10) div 2) mod 3 eq 1 and not available eq false',
                '((((price add 10) div 2) mod 3) eq 1) and (not (available eq false))',
            ],

            /**
             * 🛑 Unneeded and redundant parentheses
             */
            [
                '(((price eq 100)))',
                'price eq 100',
            ],
        ];
    }
}
