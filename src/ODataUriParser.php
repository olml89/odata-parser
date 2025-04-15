<?php

declare(strict_types=1);

namespace olml89\ODataParser;

use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Parser\Exception\ParserException;
use olml89\ODataParser\Parser\Node\Value\CastingException;
use olml89\ODataParser\Parser\Parser;

final class ODataUriParser
{
    /**
     * @throws LexerException
     * @throws ParserException
     * @throws CastingException
     */
    public function parse(string $queryString): ODataQuery
    {
        $parameters = [];

        /**
         * @var array<string, string> $parameters
         */
        parse_str($queryString, $parameters);

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

        $tokens = new Lexer($parameters[ODataParameters::filter->value])->tokenize();
        $ast = new Parser(...$tokens)->parse();

        return new ODataQuery(
            filter: $ast,
        );
    }
}
