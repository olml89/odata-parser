# OData Parser

[![PHPUnit](https://github.com/olml89/odata-parser/actions/workflows/phpunit.yml/badge.svg)](https://github.com/olml89/odata-parser)
[![Coverage](https://codecov.io/gh/olml89/odata-parser/branch/main/graph/badge.svg)](https://codecov.io/gh/olml89/odata-parser)
[![PSR-12](https://github.com/olml89/odata-parser/actions/workflows/pint.yml/badge.svg)](https://www.php-fig.org/psr/psr-12)
[![PHPMD](https://github.com/olml89/odata-parser/actions/workflows/phpmd.yml/badge.svg)](https://github.com/olml89/odata-parser)
[![PHPStan](https://github.com/olml89/odata-parser/actions/workflows/phpstan.yml/badge.svg)](https://phpstan.org/user-guide/rule-levels)

This library implements the filtering capabilities of the
[OData 4.0](https://docs.oasis-open.org/odata/odata/v4.01/odata-v4.01-part1-protocol.html)
protocol. Its goal is to parse an OData query string into an AST representing the logical structure of the query.

It has been inspired by the
[odata.net](https://github.com/OData/odata.net/tree/8a927f43e58219d45bbf15dae6e836559ba311cf)
package for C# in the .NET environment.

## Installation

This library needs **php 8.4** to run. It has no third-party dependencies.

`
composer require olml89/odata-parser
`

## Usage and error handling

Supposing we have a valid OData string, it will be converted into a syntactically valid AST representing the
logical structure of the query:

```php
$queryString = '$filter=(name eq \'John Smith\' or startswith(name, \'P\')) and age gt 18'

$query = \olml89\ODataParser\ODataUriParser::parse($queryString);
```

![AST](https://github.com/olml89/odata-parser/blob/main/img/ast.png)

If the query string is not lexically valid (for example, it contains unknown keywords), a 
`LexerException` 
will be thrown.

If the tokens for the query are valid, but they represent a syntactically invalid AST, an exception derived from
`ParserException`
will be thrown.

For example, if an expression is incomplete or there's a mismatch in parentheses, a
`UnexpectedTokenException`
will be thrown. If there's an invocation of a function with a mismatching number of arguments, an
`ArgumentCountError` 
will be thrown. If a literal operand cannot be correctly parsed into the type its token represents, a
`CastingException` will be thrown.

## Coverage of the OData 4.0 protocol

### $filter

The following operators, functions and literals are supported:

| **Operator**   | **Description**       | **Example**                                                     |
|----------------|-----------------------|-----------------------------------------------------------------|
| **Comparison** |                       |                                                                 |
| eq             | Equals                | name eq 'John Smith'                                            |
| ne             | Not equals            | name neq 'John Smith'                                           |
| lt             | Less than             | age lt 65                                                       |
| gt             | Greater than          | age gt 18                                                       |
| le             | Less than or equal    | price lte 10                                                    |
| ge             | Greater than or equal | price gte 100                                                   |
| has            | Has flags             | cities has 'Berlin'                                             |
| in             | Is a member of        | status in ('archived', 'processed')                             |
| **Collection** |                       |                                                                 |
| any            | At least one match    | orders/any(o: o/amount gt 100)                                  |
| all            | All must match        | orders/all(o: o/status eq 'open')                               |
| **Logical**    |                       |                                                                 |
| not            | Logical not (!)       | not endswith(name, 'Smith')                                     |
| and            | Logical and (&&)      | price ge 10 and price le 99                                     |
| or             | Logical or (\|\|)     | age lt 18 or age ge 65                                          |
| **Arithmetic** |                       |                                                                 |
| minus          | Negation              | age gt -()                                                      |          
| add            | Addition              | age add 5 lt age div 2                                          |
| sub            | Subtraction           | age sub 15 gt child.age                                         |
| mul            | Multiplication        | price mul 2 lt price sub discount                               |
| div            | Int division          | price div 2 gt 4                                                |
| divby          | Decimal division      | price divby 2.5 gt 4                                            |
| mod            | Modulo                | position mod 2 eq 0                                             |
| **Grouping**   |                       |                                                                 |
| ()             | Precedence grouping   | (name eq 'John Smith' or startswith(name, \'P\')) and age gt 18 |

| **Function**   | **Example**                                                        |
|----------------|--------------------------------------------------------------------|
| **String**     |                                                                    |
| concat         | concat(concat(city,', '), country) eq 'Berlin, Germany'            |
| contains       | contains(name, 'Mc')                                               |
| endswith       | endswith(name, 'lane')                                             |
| indexof        | indexof(name, 'Mc') eq 5                                           |
| length         | length(name) eq 12                                                 |
| matchesPattern | matchesPattern(name, '^([^aeiou](\w\|\s)*)\|((\w\|\s)*[^aeiou])$') |
| startswith     | startswith(name, 'John')                                           |
| substring      | substring(name, 1) eq 'ohn McClane'                                |
| tolower        | tolower(name) eq 'john mcclane'                                    |
| toupper        | toupper(name) eq 'JOHN MCCLANE'                                    |
| trim           | trim(name) eq 'johnmcclane'                                        |

| **Literal** | **Example**  |
|-------------|--------------|
| boolean     | true, false  |
| integer     | 12           |
| float       | 3.1416       |
| string      | 'John Smith' |
| null        | null         |

In OData, the order of precedence for operators is the following:
* function calls
* arithmetic operators. `mul`, `div`, `divby`and `mod` have higher precedence than `add` and `sub`
* comparison operators
* logical operators, in the following order: `not` higher than `and` higher than `or`
* identifiers
* literals

The default ordering can be altered using grouping operators: `()`. 
For example, `and` has a higher precedence than `or` (is evaluated earlier), but we can force `or` to be evaluated
earlier instead using properly the parentheses:

```php
'(name eq \'John Smith\' or startswith(name, \'P\')) and age gt 18
```

All the operation names are normalized to lower case before being processed. That means the operator and function names are
case-insensitive, although that the protocol suggests that clients willing to work with the 4.0 version MUST use
lower case operation names.








