<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Lexer\Keyword\FunctionName;

final class ArgumentCountException extends ParserException
{
    public function __construct(FunctionName $functionName, int $providedArgumentsCount, int $neededArgumentsCount)
    {
        parent::__construct(
            sprintf(
                'Function %s needs %s arguments, %s provided',
                $functionName->value,
                $neededArgumentsCount,
                $providedArgumentsCount,
            ),
        );
    }
}
