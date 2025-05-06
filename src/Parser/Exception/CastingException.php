<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Exception;

use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Node\Value\ValueType;

final class CastingException extends ParserException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidTokenKind(TokenKind $tokenKind): self
    {
        return new self(
            sprintf(
                'Token kind \'%s\' cannot be cast to a literal value',
                $tokenKind->name,
            ),
        );
    }

    public static function fromBool(string $value): self
    {
        return new self(
            sprintf(
                'Error trying to parse \'%s\' to %s',
                $value,
                ValueType::Bool->value,
            ),
        );
    }

    public static function fromNumber(string $value): self
    {
        return new self(
            sprintf(
                'Error trying to parse \'%s\' to %s, %s',
                $value,
                ValueType::Int->value,
                ValueType::Float->value,
            ),
        );
    }
}
