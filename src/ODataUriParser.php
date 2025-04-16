<?php

declare(strict_types=1);

namespace olml89\ODataParser;

use olml89\ODataParser\Lexer\Exception\LexerException;
use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Parser\Exception\ParserException;
use olml89\ODataParser\Parser\Parser;

final class ODataUriParser
{
    /**
     * @throws LexerException
     * @throws ParserException
     */
    public function parse(ODataUri $uri): ODataQuery
    {
        $tokens = new Lexer($uri->filter)->tokenize();
        $ast = new Parser(...$tokens)->parse();

        return new ODataQuery(
            filter: $ast,
        );
    }
}
