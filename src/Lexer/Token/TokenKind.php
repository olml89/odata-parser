<?php

declare(strict_types=1);

namespace olml89\ODataParser\Lexer\Token;

use olml89\ODataParser\Lexer\Keyword\ArithmeticOperator;
use olml89\ODataParser\Lexer\Keyword\CollectionOperator;
use olml89\ODataParser\Lexer\Keyword\ComparisonOperator;
use olml89\ODataParser\Lexer\Keyword\LogicalOperator;
use olml89\ODataParser\Lexer\Keyword\TypeConstant;

enum TokenKind
{
    case Identifier;
    case Function;

    /**
     * Arithmetical operators
     */
    case Minus;
    case Mul;
    case Div;
    case DivBy;
    case Mod;
    case Add;
    case Sub;

    /**
     * Logical operators
     */
    case And;
    case Or;
    case Not;

    /**
     * Comparison operators
     */
    case Equal;
    case NotEqual;
    case GreaterThan;
    case LessThan;
    case GreaterThanOrEqual;
    case LessThanOrEqual;
    case In;
    case Has;

    /**
     * Collection operators
     */
    case Any;
    case All;

    /**
     * Keywords
     */
    case Number;
    case String;
    case Boolean;
    case Null;

    /**
     * Special chars
     */
    case OpenParen;
    case CloseParen;
    case SingleQuote;
    case Comma;
    case Dot;
    case Colon;
    case Slash;

    public function is(TokenKind ...$tokenKinds): bool
    {
        return array_any(
            $tokenKinds,
            fn (TokenKind $tokenKind): bool => $this === $tokenKind,
        );
    }

    public static function fromArithmeticOperator(ArithmeticOperator $arithmeticOperator): self
    {
        return match ($arithmeticOperator) {
            ArithmeticOperator::mul => self::Mul,
            ArithmeticOperator::div => self::Div,
            ArithmeticOperator::divBy => self::DivBy,
            ArithmeticOperator::mod => self::Mod,
            ArithmeticOperator::add => self::Add,
            ArithmeticOperator::sub => self::Sub,
        };
    }

    public static function fromLogicalOperator(LogicalOperator $logicalOperator): self
    {
        return match ($logicalOperator) {
            LogicalOperator::and => self::And,
            LogicalOperator::or => self::Or,
            LogicalOperator::not => self::Not,
        };
    }

    public static function fromComparisonOperator(ComparisonOperator $comparisonOperator): self
    {
        return match ($comparisonOperator) {
            ComparisonOperator::eq => self::Equal,
            ComparisonOperator::ne => self::NotEqual,
            ComparisonOperator::gt => self::GreaterThan,
            ComparisonOperator::lt => self::LessThan,
            ComparisonOperator::ge => self::GreaterThanOrEqual,
            ComparisonOperator::le => self::LessThanOrEqual,
            ComparisonOperator::in => self::In,
            ComparisonOperator::has => self::Has,
        };
    }

    public static function fromCollectionOperator(CollectionOperator $collectionOperator): self
    {
        return match ($collectionOperator) {
            CollectionOperator::any => self::Any,
            CollectionOperator::all => self::All,
        };
    }

    public static function fromTypeConstant(TypeConstant $typeConstant): self
    {
        return match ($typeConstant) {
            TypeConstant::null => self::Null,
            TypeConstant::true, TypeConstant::false => self::Boolean,
        };
    }
}
