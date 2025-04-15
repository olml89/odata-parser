<?php

declare(strict_types=1);

namespace olml89\ODataParser;

use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Lexer\LexerException;
use olml89\ODataParser\Parser\Exception\OutOfBoundsException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\Node\Function\ArgumentCountException;
use olml89\ODataParser\Parser\Node\Value\CastingException;
use olml89\ODataParser\Parser\Parser;

final class ODataUriParser
{
    /**
     * @throws LexerException
     * @throws OutOfBoundsException
     * @throws ArgumentCountException
     * @throws UnexpectedTokenException
     * @throws CastingException
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
