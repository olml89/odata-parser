<?php

declare(strict_types=1);

namespace Tests\Helper\City;

use Tests\Helper\City\Entity\City;
use Tests\Helper\City\Entity\District;
use Tests\Helper\City\Entity\Geolocation;
use Tests\Helper\City\Entity\Latitude;
use Tests\Helper\City\Entity\Longitude;
use Tests\Helper\City\Entity\Name;
use Tests\Helper\City\Entity\Neighborhood;
use Tests\Helper\City\Entity\PoliticalParty;
use Tests\Helper\City\Entity\Population;

final readonly class CityFactory
{
    /**
     * @param string[] $tags
     * @param District[] $districts
     */
    public static function create(
        string $name = 'City',
        float $lat = 0.0,
        float $lon = 0.0,
        int $population = 100,
        PoliticalParty $major = PoliticalParty::Conservative,
        bool $isCapital = false,
        array $tags = [],
        array $districts = [],
    ): City {
        return new City(
            new Name($name),
            new Geolocation(new Latitude($lat), new Longitude($lon)),
            new Population($population),
            $major,
            $isCapital,
            $tags,
            $districts,
        );
    }

    public static function beijing(): City
    {
        return self::create(
            name: 'Beijing',
            lat: 39.9042,
            lon: 116.4074,
            population: 21300000,
            major: PoliticalParty::Conservative,
            isCapital: true,
            tags: ['imperial', 'political', 'cultural'],
            districts: [
                new District(
                    name: new Name('Dongcheng'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Wangfujing'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Dongcheng'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Jingshan'), registeredSchoolsCount: 5),
                        new Neighborhood(name: new Name('Nanxincang'), registeredSchoolsCount: 7),
                    ],
                ),
                new District(
                    name: new Name('Xicheng'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Xidan'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Majiapu'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Xicheng'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Taoranting'), registeredSchoolsCount: 6),
                    ],
                ),
                new District(
                    name: new Name('Chaoyang'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Sanlitun'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Wangjing'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Shuangjing'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Chaoyang Park'), registeredSchoolsCount: 18),
                    ],
                ),
                new District(
                    name: new Name('Haidian'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Zhongguancun'), registeredSchoolsCount: 30),
                        new Neighborhood(name: new Name('Wudaokou'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Haidian'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Xueyuan Road'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('Fengtai'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Fengtai'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Baiyun'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Lize'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Fangzhuang'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('Shijingshan'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Shijingshan'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Yuyuan'), registeredSchoolsCount: 5),
                        new Neighborhood(name: new Name('Liuliqiao'), registeredSchoolsCount: 7),
                    ],
                ),
            ],
        );
    }

    public static function cairo(): City
    {
        return self::create(
            name: 'Cairo',
            lat: 30.0444,
            lon: 31.2357,
            population: 22200000,
            major: PoliticalParty::Conservative,
            isCapital: true,
            tags: ['ancient', 'desert', 'bustling'],
            districts: [
                new District(
                    name: new Name('Helwan'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Maadi'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Helwan'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('El Zahraa'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Wadi Hof'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Maadi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('New Maadi'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Old Maadi'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Maadi Degla'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Maadi Sarayat'), registeredSchoolsCount: 7),
                    ],
                ),
                new District(
                    name: new Name('Nasr City'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('10th of Ramadan'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('City Center'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('El Manteqah El Thamenah'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Heliopolis'), registeredSchoolsCount: 22),
                    ],
                ),
                new District(
                    name: new Name('Zamalek'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Zamalek'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Garden City'), registeredSchoolsCount: 7),
                        new Neighborhood(name: new Name('Opera'), registeredSchoolsCount: 5),
                        new Neighborhood(name: new Name('Giza'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('Heliopolis'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Heliopolis'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Nozha'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Misr El Gedida'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Shahria'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Shubra'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Shubra'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Shoubra El-Kheima'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('El-Basatin'), registeredSchoolsCount: 12),
                    ],
                ),
            ],
        );
    }

    public static function ciudadDeMexico(): City
    {
        return self::create(
            name: 'Ciudad de México',
            lat: 19.4326,
            lon: -99.1332,
            population: 22300000,
            major: PoliticalParty::Progressive,
            isCapital: true,
            tags: ['historic', 'artistic', 'foodie'],
            districts: [
                new District(
                    name: new Name('Coyoacán'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Coyoacán Centro'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Copilco'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Villa Coapa'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Pedregal'), registeredSchoolsCount: 20),
                    ],
                ),
                new District(
                    name: new Name('Iztapalapa'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('San Lázaro'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Iztapalapa'), registeredSchoolsCount: 35),
                        new Neighborhood(name: new Name('Lomas de la Estancia'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Cerro de la Estrella'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Tlalpan'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Tlalpan'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('La Joya'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Fuentes Brotantes'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Insurgentes'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Gustavo A. Madero'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Villa de Guadalupe'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Lindavista'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Guerrero'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Morelos'), registeredSchoolsCount: 20),
                    ],
                ),
                new District(
                    name: new Name('Benito Juárez'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Mixcoac'), registeredSchoolsCount: 7),
                        new Neighborhood(name: new Name('San José'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Portero'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Napoles'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Álvaro Obregón'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Santa Fe'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Tlaltenco'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('San Bartolo'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('San Ángel'), registeredSchoolsCount: 15),
                    ],
                ),
            ],
        );
    }

    public static function delhi(): City
    {
        return self::create(
            name: 'Delhi',
            lat: 28.7041,
            lon: 77.1025,
            population: 32900000,
            major: PoliticalParty::Progressive,
            isCapital: true,
            tags: ['historic', 'crowded', 'colorful'],
            districts: [
                new District(
                    name: new Name('Central Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Connaught Place'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Karol Bagh'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Paharganj'), registeredSchoolsCount: 7),
                    ],
                ),
                new District(
                    name: new Name('New Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Chanakyapuri'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Lodhi Road'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Raisina Hill'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('South Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Saket'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Greater Kailash'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Lajpat Nagar'), registeredSchoolsCount: 14),
                    ],
                ),
                new District(
                    name: new Name('North Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Shahadra'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Burari'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Azadpur'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('West Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Rajouri Garden'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Punjabi Bagh'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Moti Nagar'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('East Delhi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Preet Vihar'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Karkardooma'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Mayur Vihar'), registeredSchoolsCount: 8),
                    ],
                ),
            ],
        );
    }

    public static function dhaka(): City
    {
        return self::create(
            name: 'Dhaka',
            lat: 23.8103,
            lon: 90.4125,
            population: 20200000,
            major: PoliticalParty::Progressive,
            isCapital: true,
            tags: ['growing', 'textile', 'dense'],
            districts: [
                new District(
                    name: new Name('Gulshan'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Gulshan-1'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Gulshan-2'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Banani'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Niketan'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Dhanmondi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Dhanmondi 1-27'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Dhanmondi 28-50'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Shankar'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Kalabagan'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Mirpur'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Mirpur-1'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Mirpur-10'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Pallabi'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Shewrapara'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Motijheel'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Motijheel Model Town'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Sadarghat'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Kotwali'), registeredSchoolsCount: 7),
                    ],
                ),
                new District(
                    name: new Name('Uttara'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Uttara Sector 1-10'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Uttara Sector 11-12'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Jashimuddin'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Banani'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Banani'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Kuril'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Badda'), registeredSchoolsCount: 15),
                    ],
                ),
            ],
        );
    }

    public static function mumbai(): City
    {
        return self::create(
            name: 'Mumbai',
            lat: 19.0760,
            lon: 72.8777,
            population: 21700000,
            major: PoliticalParty::Progressive,
            isCapital: false,
            tags: ['cinema', 'finance', 'seaside'],
            districts: [
                new District(
                    name: new Name('South Mumbai'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Colaba'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Nariman Point'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Fort'), registeredSchoolsCount: 18),
                    ],
                ),
                new District(
                    name: new Name('Bandra'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Bandra West'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Bandra East'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Khar'), registeredSchoolsCount: 20),
                    ],
                ),
                new District(
                    name: new Name('Andheri'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Andheri West'), registeredSchoolsCount: 30),
                        new Neighborhood(name: new Name('Andheri East'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Lokhandwala'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Juhu'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Juhu Beach'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Vile Parle'), registeredSchoolsCount: 22),
                        new Neighborhood(name: new Name('Santacruz'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Thane'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Thane West'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Thane East'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Vartak Nagar'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Mulund'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Mulund West'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Mulund East'), registeredSchoolsCount: 10),
                    ],
                ),
            ],
        );
    }

    public static function newYork(): City
    {
        return self::create(
            name: 'New York',
            lat: 40.7128,
            lon: -74.0060,
            population: 19800000,
            major: PoliticalParty::Conservative,
            isCapital: false,
            tags: ['cosmopolitan', 'finance', 'arts'],
            districts: [
                new District(
                    name: new Name('Manhattan'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Upper East Side'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Upper West Side'), registeredSchoolsCount: 22),
                        new Neighborhood(name: new Name('Chelsea'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Greenwich Village'), registeredSchoolsCount: 18),
                    ],
                ),
                new District(
                    name: new Name('Brooklyn'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Williamsburg'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Park Slope'), registeredSchoolsCount: 22),
                        new Neighborhood(name: new Name('Brooklyn Heights'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Coney Island'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Queens'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Astoria'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Flushing'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Jackson Heights'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Forest Hills'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Bronx'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Fordham'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Riverdale'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('South Bronx'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Staten Island'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('St. George'), registeredSchoolsCount: 5),
                        new Neighborhood(name: new Name('Tottenville'), registeredSchoolsCount: 7),
                    ],
                ),
            ],
        );
    }

    public static function shangai(): City
    {
        return self::create(
            name: 'Shanghai',
            lat: 31.2304,
            lon: 121.4737,
            population: 29200000,
            major: PoliticalParty::Conservative,
            isCapital: false,
            tags: ['finance', 'modern', 'riverfront'],
            districts: [
                new District(
                    name: new Name('Pudong'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Lujiazui'), registeredSchoolsCount: 30),
                        new Neighborhood(name: new Name('Jinqiao'), registeredSchoolsCount: 25),
                        new Neighborhood(name: new Name('Zhangjiang'), registeredSchoolsCount: 18),
                    ],
                ),
                new District(
                    name: new Name('Huangpu'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Nanjing Road'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('People\'s Square'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Xintiandi'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Changning'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Hongqiao'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Jiangsu Road'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Gubei'), registeredSchoolsCount: 15),
                    ],
                ),
                new District(
                    name: new Name('Yangpu'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Fudan University'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Wujiaochang'), registeredSchoolsCount: 18),
                    ],
                ),
                new District(
                    name: new Name('Minhang'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Xinzhuang'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Dongchuan Road'), registeredSchoolsCount: 10),
                    ],
                ),
            ],
        );
    }

    public static function saoPaulo(): City
    {
        return self::create(
            name: 'São Paulo',
            lat: -23.5505,
            lon: -46.6333,
            population: 22600000,
            major: PoliticalParty::Conservative,
            isCapital: false,
            tags: ['vibrant', 'diverse', 'nightlife'],
            districts: [
                new District(
                    name: new Name('Centro'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Sé'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('República'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Liberdade'), registeredSchoolsCount: 20),
                    ],
                ),
                new District(
                    name: new Name('Pinheiros'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Vila Madalena'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Alto de Pinheiros'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Brooklin'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Brooklin Novo'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Vila Progredior'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('Moema'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Moema Pássaros'), registeredSchoolsCount: 8),
                        new Neighborhood(name: new Name('Moema Índios'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Itaim Bibi'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Itaim Bibi'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Vila Progredior'), registeredSchoolsCount: 15),
                    ],
                ),
            ],
        );
    }

    public static function tokio(): City
    {
        return self::create(
            name: 'Tokio',
            lat: 35.6895,
            lon: 139.6917,
            population: 37400000,
            major: PoliticalParty::Conservative,
            isCapital: true,
            tags: ['technology', 'pop-culture', 'efficient'],
            districts: [
                new District(
                    name: new Name('Shibuya'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Harajuku'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Shibuya Center'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Ebisu'), registeredSchoolsCount: 10),
                    ],
                ),
                new District(
                    name: new Name('Shinjuku'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Kabukicho'), registeredSchoolsCount: 10),
                        new Neighborhood(name: new Name('Takadanobaba'), registeredSchoolsCount: 12),
                        new Neighborhood(name: new Name('Shinjuku Sumo District'), registeredSchoolsCount: 8),
                    ],
                ),
                new District(
                    name: new Name('Minato'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Roppongi'), registeredSchoolsCount: 18),
                        new Neighborhood(name: new Name('Akasaka'), registeredSchoolsCount: 15),
                        new Neighborhood(name: new Name('Azabu'), registeredSchoolsCount: 12),
                    ],
                ),
                new District(
                    name: new Name('Chiyoda'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Imperial Palace'), registeredSchoolsCount: 5),
                        new Neighborhood(name: new Name('Otemachi'), registeredSchoolsCount: 7),
                    ],
                ),
                new District(
                    name: new Name('Setagaya'),
                    neighborhoods: [
                        new Neighborhood(name: new Name('Kichijoji'), registeredSchoolsCount: 20),
                        new Neighborhood(name: new Name('Sangenjaya'), registeredSchoolsCount: 18),
                    ],
                ),
            ],
        );
    }
}
