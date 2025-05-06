<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Exception;

final class InvalidRegExpException extends SemanticException
{
    public function __construct(string $invalidRegExp)
    {
        parent::__construct(
            sprintf(
                'Expected a valid regular expression, got \'%s\'',
                $invalidRegExp,
            ),
        );
    }
}
