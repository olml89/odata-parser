<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\InvalidTokenException;
use olml89\ODataParser\Lexer\Exception\LexerException;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\Token;

final readonly class ScannerPipeline implements Scanner
{
    /**
     * @var Scanner[]
     */
    private array $pipeline;

    private ?Source $source;

    public function __construct(?Source $source)
    {
        $this->source = $source;

        $this->pipeline = is_null($this->source) ? [] : [
            new KeywordScanner($this->source),
            new SpecialCharScanner($this->source),
            new IdentifierScanner($this->source),
            new NumericScanner($this->source),
            new StringScanner($this->source),
        ];
    }

    public function eof(): bool
    {
        return $this->source?->eof() ?? true;
    }

    /**
     * @throws LexerException
     */
    public function scan(): ?Token
    {
        if (is_null($this->source) || $this->source->eof()) {
            return null;
        }

        $this->source->consumeWhiteSpaces();

        if ($this->source->eof()) {
            return null;
        }

        foreach ($this->pipeline as $scanner) {
            $token = $scanner->scan();

            if (!is_null($token)) {
                return $token;
            }
        }

        throw new InvalidTokenException($this->source->peek());
    }
}
