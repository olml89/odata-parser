<?php

declare(strict_types=1);

namespace olml89\ODataParser;

final readonly class ODataUri
{
    public ?string $filter;

    public function __construct(string $uri)
    {
        $parameters = [];

        /**
         * @var array<string, string> $parameters
         */
        parse_str($uri, $parameters);

        /**
         * @var array<lowercase-string, string> $parameters
         */
        $keys = array_keys($parameters);
        $parameters = array_combine(
            array_map(
                fn (string $key): string => mb_strtolower($key),
                $keys,
            ),
            array_values($parameters),
        );

        $this->filter = $parameters[ODataParameters::filter->value] ?? null;
    }
}
