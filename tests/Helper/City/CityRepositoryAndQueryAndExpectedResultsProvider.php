<?php

declare(strict_types=1);

namespace Tests\Helper\City;

final readonly class CityRepositoryAndQueryAndExpectedResultsProvider
{
    /**
     * @return array<string, list<mixed>>
     */
    public static function provide(): array
    {
        return [

            /**
             * Function
             */
            'concat' => [
                new CityRepository(),
                'concat(name, \'r\') eq \'Dhakar\'',
                CityFactory::dhaka(),
            ],
            'contains (strings)' => [
                new CityRepository(),
                'contains(name, \'ai\')',
                CityFactory::cairo(),
                CityFactory::mumbai(),
                CityFactory::shangai(),
            ],
            'contains (collection of strings)' => [
                new CityRepository(),
                'contains(tags, \'finance\')',
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
            ],
            'endswith' => [
                new CityRepository(),
                'endsWith(name, \'ai\')',
                CityFactory::mumbai(),
                CityFactory::shangai(),
            ],
            'indexof' => [
                new CityRepository(),
                'indexof(name, \'e\') eq 1',
                CityFactory::beijing(),
                CityFactory::delhi(),
                CityFactory::newYork(),
            ],
            'length' => [
                new CityRepository(),
                'length(name) eq 5',
                CityFactory::cairo(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::tokio(),
            ],
            'matchesPattern' => [
                new CityRepository(),
                'matchesPattern(name, \'/x/i\')',
                CityFactory::ciudadDeMexico(),
            ],
            'startswith' => [
                new CityRepository(),
                'startswith(name, \'D\')',
                CityFactory::delhi(),
                CityFactory::dhaka(),
            ],
            'substring' => [
                new CityRepository(),
                'substring(name, 7, 2) eq \'de\'',
                CityFactory::ciudadDeMexico(),
            ],
            'tolower' => [
                new CityRepository(),
                'tolower(name) eq \'beijing\'',
                CityFactory::beijing(),
            ],
            'toupper' => [
                new CityRepository(),
                'toupper(name) eq \'BEIJING\'',
                CityFactory::beijing(),
            ],
            'trim' => [
                new CityRepository($city = CityFactory::create(name: '     City     ')),
                'trim(name) eq \'City\'',
                $city,
            ],

            /**
             * Arithmetic
             */
            'add with integers' => [
                new CityRepository(),
                '(population add 1) eq 22600001',
                CityFactory::saoPaulo(),
            ],
            'add with normalizable floats' => [
                new CityRepository(),
                '(population add 1.0) eq 22600001',
                CityFactory::saoPaulo(),
            ],
            'add with floats' => [
                new CityRepository(),
                '(population add 1.25) eq 22600001.25',
                CityFactory::saoPaulo(),
            ],
            'sub with integers' => [
                new CityRepository(),
                '(population sub 1) eq 22599999',
                CityFactory::saoPaulo(),
            ],
            'sub with normalizable floats' => [
                new CityRepository(),
                '(population sub 1.0) eq 22599999',
                CityFactory::saoPaulo(),
            ],
            'sub with floats' => [
                new CityRepository(),
                '(population sub 1.25) eq 22599998.75',
                CityFactory::saoPaulo(),
            ],
            'mul' => [
                new CityRepository(),
                '(population mul 4) ge 100000000',
                CityFactory::delhi(),
                CityFactory::shangai(),
                CityFactory::tokio(),
            ],
            'div with integers yielding an exact result' => [
                new CityRepository(),
                '(population div 10) eq 2260000',
                CityFactory::saoPaulo(),
            ],
            'div with integers yielding a truncated result' => [
                new CityRepository(),
                '(population div 15) eq 1506666',
                CityFactory::saoPaulo(),
            ],
            'div with floats' => [
                new CityRepository(),
                '(population div 10.7) eq 2112149.5327102807',
                CityFactory::saoPaulo(),
            ],
            'divby with integers' => [
                new CityRepository(),
                '(population divby 10) eq 2260000',
                CityFactory::saoPaulo(),
            ],
            'divby with integers yielding a float result' => [
                new CityRepository(),
                '(population divby 15) eq 1506666.6666666667',
                CityFactory::saoPaulo(),
            ],
            'divby with floats' => [
                new CityRepository(),
                '(population divby 10.7) eq 2112149.5327102807',
                CityFactory::saoPaulo(),
            ],
            'mod' => [
                new CityRepository(
                    $city = CityFactory::create(population: 10000000),
                    CityFactory::create(population: 24999995),
                ),
                '(population mod 2) eq 0',
                $city,
            ],
            'minus with properties' => [
                new CityRepository(),
                '-geolocation/longitude eq 99.1332',
                CityFactory::ciudadDeMexico(),
            ],
            'minus with literals' => [
                new CityRepository(),
                'population eq -(-22300000)',
                CityFactory::ciudadDeMexico(),
            ],
            'minus with properties and literals' => [
                new CityRepository(),
                '-(population) eq -22300000',
                CityFactory::ciudadDeMexico(),
            ],

            /**
             * Comparison
             */
            'all' => [
                new CityRepository(),
                'tags/all(t: t eq \'finance\')',
            ],
            // Cities with all the districts with all the neighborhoods with at least 10 registered schools
            'all with nested alls' => [
                new CityRepository(),
                'districts/all(d: d/neighborhoods/all(n: n/registeredSchoolsCount ge 10))',
                CityFactory::mumbai(),
                CityFactory::shangai(),
            ],
            'all with access to the outer scope' => [
                new CityRepository(),
                'tags/all(t: t eq \'finance\' or population gt 1000)',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],
            'any' => [
                new CityRepository(),
                'tags/any(t: t eq \'finance\')',
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
            ],
            // Cities with at least a district with at least a neighborhood with 30 or more schools
            'any with nested anys' => [
                new CityRepository(),
                'districts/any(d: d/neighborhoods/any(n: n/registeredSchoolsCount ge 30))',
                CityFactory::beijing(),
                CityFactory::ciudadDeMexico(),
                CityFactory::mumbai(),
                CityFactory::shangai(),
            ],
            'any with access to the outer scope' => [
                new CityRepository(),
                'tags/any(t: t eq \'abcde\' or population gt 1000)',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],
            'equal with strings' => [
                new CityRepository(),
                'name eq \'Beijing\'',
                CityFactory::beijing(),
            ],
            'equal with numbers' => [
                new CityRepository(),
                'geolocation/latitude eq 23.8103',
                CityFactory::dhaka(),
            ],
            'equal with enums' => [
                new CityRepository(),
                'governedBy eq \'conservative\'',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],
            // Cities past parallel 0: all except Ciudad de México, New York, Sao Paulo
            'greater than' => [
                new CityRepository(),
                'geolocation/longitude gt 0',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::shangai(),
                CityFactory::tokio(),
            ],
            // Cities with more than 20000000 citizens: all except New York
            'greater than or equal' => [
                new CityRepository(),
                'population ge 20000000',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],
            /*
            'has' => [

            ],
            */
            'in' => [
                new CityRepository(),
                'name in (\'Cairo\', \'Tokio\')',
                CityFactory::cairo(),
                CityFactory::tokio(),
            ],
            'less than' => [
                new CityRepository(),
                'geolocation/latitude lt 0',
                CityFactory::saoPaulo(),
            ],
            'less than or equal' => [
                new CityRepository(),
                'geolocation/longitude le 31.2357',
                CityFactory::cairo(),
                CityFactory::ciudadDeMexico(),
                CityFactory::newYork(),
                CityFactory::saoPaulo(),
            ],
            'not equal' => [
                new CityRepository(),
                'name ne \'Beijing\'',
                CityFactory::cairo(),
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],
            'not equal with enums' => [
                new CityRepository(),
                'governedBy ne \'conservative\'',
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
            ],

            /**
             * Logical
             */
            'not' => [
                new CityRepository(),
                'not (name in (\'Cairo\', \'Tokio\'))',
                CityFactory::beijing(),
                CityFactory::ciudadDeMexico(),
                CityFactory::delhi(),
                CityFactory::dhaka(),
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
            ],
            // Finance cities which are conservative (excludes Mumbai which is finance but progressive)
            'and' => [
                new CityRepository(),
                '(governedBy eq \'conservative\') and (tags/any(t: t eq \'finance\'))',
                CityFactory::newYork(),
                CityFactory::shangai(),
            ],
            // Finance cities or conservative cities (all finance cities and all conservative cities, includes
            // Mumbai which is progressive but finance)
            'or' => [
                new CityRepository(),
                '(governedBy eq \'conservative\') or (tags/any(t: t eq \'finance\'))',
                CityFactory::beijing(),
                CityFactory::cairo(),
                CityFactory::mumbai(),
                CityFactory::newYork(),
                CityFactory::shangai(),
                CityFactory::saoPaulo(),
                CityFactory::tokio(),
            ],

        ];
    }
}
