<?php

declare(strict_types=1);

namespace olml89\ODataParser\Parser\Node\Value;

use olml89\ODataParser\Lexer\Keyword\TypeConstant;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use Stringable;

abstract readonly class Value implements Stringable
{
    /**
     * @throws CastingException
     */
    public static function fromValueToken(ValueToken $valueToken): ?Value
    {
        return match ($valueToken->kind) {
            TokenKind::Boolean => self::castBoolean($valueToken),
            TokenKind::Number => self::castNumber($valueToken),
            TokenKind::String => new StringValue($valueToken->value),
            default => null,
        };
    }

    /**
     * @throws CastingException
     */
    private static function castBoolean(ValueToken $token): BooleanValue
    {
        $value = TypeConstant::from($token->value)->value;
        $boolCast = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (is_null($boolCast)) {
            throw CastingException::fromBool($value);
        }

        return new BooleanValue($boolCast);
    }

    /**
     * @throws CastingException
     */
    private static function castNumber(ValueToken $valueToken): IntValue|FloatValue
    {
        $value = $valueToken->value;
        $floatCast = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        if (!is_null($floatCast) && round($floatCast) !== $floatCast) {
            return new FloatValue($floatCast);
        }

        $intCast = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (is_null($intCast)) {
            throw CastingException::fromInt($value);
        }

        return new IntValue($intCast);
    }
}
