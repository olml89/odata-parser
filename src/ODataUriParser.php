<?php

declare(strict_types=1);

namespace olml89\ODataParser;

final class ODataUriParser
{
    /**
     * @param string $uri
     * @return array<int, string>
     */
    public static function parse(string $uri): array
    {
        return [
            $uri,
        ];
    }
}
