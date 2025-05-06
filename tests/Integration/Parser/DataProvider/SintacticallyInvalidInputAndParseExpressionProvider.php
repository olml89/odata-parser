<?php

declare(strict_types=1);

namespace Tests\Integration\Parser\DataProvider;

use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\ArgumentCountException;
use olml89\ODataParser\Parser\Exception\ParserException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;

final readonly class SintacticallyInvalidInputAndParseExpressionProvider
{
    /**
     * @return array<string, array{0: string, 1: ParserException}>
     */
    public static function provide(): array
    {
        return [
            'simple expression without literal' => [
                'name eq',
                UnexpectedTokenException::position(
                    new OperatorToken(TokenKind::Equal),
                    position: 1,
                )
            ],
            'void expression inside parentheses' => [
                '()',
                UnexpectedTokenException::position(
                    new OperatorToken(TokenKind::CloseParen),
                    position: 1,
                ),
            ],
            'function with missing arguments' => [
                'substring(name)',
                new ArgumentCountException(
                    functionName: FunctionName::substring,
                    providedArgumentsCount: 1,
                    neededArgumentsCount: 3,
                ),
            ],
        ];
    }
}
