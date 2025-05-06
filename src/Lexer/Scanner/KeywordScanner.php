<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Scanner;

use olml89\ODataParser\Lexer\Exception\CharOutOfBoundsException;
use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\FunctionName;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;

final readonly class KeywordScanner implements Scanner
{
    use IsScanner;

    /**
     * @throws CharOutOfBoundsException
     */
    public function scan(): ?Token
    {
        /**
         * In OData, the order of precedence for operators from top to bottom is:
         * 1) Parentheses
         * 2) Functions
         * 3) Arithmetical operators (mul, div, mod), (add, sub), minus (-)
         * 4) Comparison operators
         * 5) Logical operator NOT
         * 6) Logical operator AND
         * 7) Logical operator OR
         */
        $keywords = [
            ...FunctionName::cases(),
            ...ArithmeticOperator::cases(),
            ...ComparisonOperator::cases(),
            ...LogicalOperator::cases(),
            ...CollectionOperator::cases(),
            ...TypeConstant::cases(),
        ];

        $keyword = $this->source->find(...$keywords);

        return match (true) {
            $keyword instanceof FunctionName => new ValueToken(
                TokenKind::Function,
                $keyword->value,
            ),
            $keyword instanceof ArithmeticOperator => new OperatorToken(
                TokenKind::fromArithmeticOperator($keyword)
            ),
            $keyword instanceof ComparisonOperator => new OperatorToken(
                TokenKind::fromComparisonOperator($keyword),
            ),
            $keyword instanceof LogicalOperator => new OperatorToken(
                TokenKind::fromLogicalOperator($keyword),
            ),
            $keyword instanceof CollectionOperator => new OperatorToken(
                TokenKind::fromCollectionOperator($keyword),
            ),
            $keyword instanceof TypeConstant => new ValueToken(
                TokenKind::fromTypeConstant($keyword),
                $keyword->value,
            ),
            default => null,
        };
    }
}
