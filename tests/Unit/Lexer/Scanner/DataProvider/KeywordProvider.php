<?php

declare(strict_types=1);

namespace Tests\Unit\Lexer\Scanner\DataProvider;

final readonly class KeywordProvider
{
    /**
     * @return list<list<string>>
     */
    public static function provide(): array
    {
        return [
            /**
             * Functions
             */
            ['concat()'],
            ['contains()'],
            ['endswith()'],
            ['indexof()'],
            ['length()'],
            ['matchesPattern()'],
            ['startswith()'],
            ['substring()'],
            ['tolower()'],
            ['toupper()'],
            ['trim()'],

            /**
             * Arithmetic operators
             */
            ['mul'],
            ['divby'],
            ['div'],
            ['mod'],
            ['add'],
            ['sub'],

            /**
             * Comparison operators
             */
            ['eq'],
            ['ne'],
            ['gt'],
            ['ge'],
            ['lt'],
            ['le'],
            ['in'],
            ['has'],

            /**
             * Logical operators
             */
            ['not'],
            ['and'],
            ['or'],

            /**
             * Collection operators
             */
            ['any'],
            ['all'],

            /**
             * Type constants
             */
            ['true'],
            ['false'],
            ['null'],
        ];
    }
}
