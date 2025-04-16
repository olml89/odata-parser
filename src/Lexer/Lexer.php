<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer;

use olml89\ODataParser\Lexer\Exception\LexerException;
use olml89\ODataParser\Lexer\Scanner\ScannerPipeline;
use olml89\ODataParser\Lexer\Token\Token;

final readonly class Lexer
{
    private ScannerPipeline $scannerPipeline;

    public function __construct(?string $text)
    {
        $source = Source::load($text);

        $this->scannerPipeline = new ScannerPipeline($source);
    }

    /**
     * @return Token[]
     *
     * @throws LexerException
     */
    public function tokenize(): array
    {
        $tokens = [];

        while (!is_null($token = $this->scannerPipeline->scan())) {
            $tokens[] = $token;
        }

        return $tokens;
    }
}
