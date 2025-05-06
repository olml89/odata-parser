<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Exception\CastingException;

final readonly class ScalarCaster
{
    /**
     * @throws CastingException
     */
    public function cast(ValueToken $valueToken): Scalar
    {
        return match ($valueToken->kind) {
            TokenKind::Null => new NullValue(),
            TokenKind::Boolean => $this->castBoolean($valueToken),
            TokenKind::Number => $this->castNumber($valueToken),
            TokenKind::String => new StringValue($valueToken->value),
            default => throw CastingException::invalidTokenKind($valueToken->kind),
        };
    }

    /**
     * @throws CastingException
     */
    private static function castBoolean(ValueToken $token): BoolValue
    {
        $value = $token->value;
        $boolCast = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (is_null($boolCast)) {
            throw CastingException::fromBool($value);
        }

        return new BoolValue($boolCast);
    }

    /**
     * @throws CastingException
     */
    private static function castNumber(ValueToken $token): Number
    {
        $value = $token->value;
        $floatCast = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        if (!is_null($floatCast)) {
            return new FloatValue($floatCast)->normalize();
        }

        $intCast = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (is_null($intCast)) {
            throw CastingException::fromNumber($value);
        }

        return new IntValue($intCast);
    }
}
