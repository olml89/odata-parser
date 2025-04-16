<?php

declare(strict_types=1);

namespace Tests\Integration\Parser;

use olml89\ODataParser\Lexer\Char;
use olml89\ODataParser\Lexer\Keyword\IsNotChar;
use olml89\ODataParser\Lexer\Keyword\SpecialChar;
use olml89\ODataParser\Lexer\Lexer;
use olml89\ODataParser\Lexer\Scanner\IdentifierScanner;
use olml89\ODataParser\Lexer\Scanner\IsScanner;
use olml89\ODataParser\Lexer\Scanner\KeywordScanner;
use olml89\ODataParser\Lexer\Scanner\NumericScanner;
use olml89\ODataParser\Lexer\Scanner\ScannerPipeline;
use olml89\ODataParser\Lexer\Scanner\SpecialCharScanner;
use olml89\ODataParser\Lexer\Scanner\StringScanner;
use olml89\ODataParser\Lexer\Source;
use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Lexer\Token\ValueToken;
use olml89\ODataParser\Parser\Exception\ParserException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\Node\Function\ArgumentCountException;
use olml89\ODataParser\Parser\Node\Function\BinaryFunction;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\UnaryFunction;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\HasHighPreference;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\HasLowPreference;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\IsArithmetic;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Operator\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Operator\BinaryOperator;
use olml89\ODataParser\Parser\Node\Operator\CollectionLambdaOperator;
use olml89\ODataParser\Parser\Node\Operator\Comparison\All;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Any;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Operator\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Operator\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Operator\Comparison\Has;
use olml89\ODataParser\Parser\Node\Operator\Comparison\In;
use olml89\ODataParser\Parser\Node\Operator\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Operator\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Operator\Logical\AndOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\NotOperator;
use olml89\ODataParser\Parser\Node\Operator\Logical\OrOperator;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BooleanValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\Value;
use olml89\ODataParser\Parser\Parser;
use olml89\ODataParser\Parser\TokenManager;
use olml89\ODataParser\Parser\TokenWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Parser\DataProvider\OperatorPrecedenceProvider;
use Tests\Integration\Parser\DataProvider\SintacticallyInvalidInputAndParseExpressionProvider;

#[CoversClass(Lexer::class)]
#[CoversClass(Parser::class)]
#[UsesClass(Add::class)]
#[UsesClass(All::class)]
#[UsesClass(Any::class)]
#[UsesClass(AndOperator::class)]
#[UsesClass(ArgumentCountException::class)]
#[UsesClass(BinaryFunction::class)]
#[UsesClass(BinaryOperator::class)]
#[UsesClass(BooleanValue::class)]
#[UsesClass(CollectionLambdaOperator::class)]
#[UsesClass(Char::class)]
#[UsesClass(Div::class)]
#[UsesClass(EndsWith::class)]
#[UsesClass(Equal::class)]
#[UsesClass(GreaterThan::class)]
#[UsesClass(GreaterThanOrEqual::class)]
#[UsesClass(Has::class)]
#[UsesClass(IdentifierScanner::class)]
#[UsesClass(In::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(KeywordScanner::class)]
#[UsesClass(LessThan::class)]
#[UsesClass(Literal::class)]
#[UsesClass(Mul::class)]
#[UsesClass(Mod::class)]
#[UsesClass(NotEqual::class)]
#[UsesClass(NotOperator::class)]
#[UsesClass(NumericScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(OrOperator::class)]
#[UsesClass(Property::class)]
#[UsesClass(ScannerPipeline::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
#[UsesClass(SpecialCharScanner::class)]
#[UsesClass(StringScanner::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(StartsWith::class)]
#[UsesClass(Sub::class)]
#[UsesClass(Substring::class)]
#[UsesClass(ToLower::class)]
#[UsesClass(TokenManager::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(TokenWrapper::class)]
#[UsesClass(UnexpectedTokenException::class)]
#[UsesClass(Value::class)]
#[UsesClass(ValueToken::class)]
#[UsesClass(UnaryFunction::class)]
#[UsesTrait(HasHighPreference::class)]
#[UsesTrait(HasLowPreference::class)]
#[UsesTrait(IsArithmetic::class)]
#[UsesTrait(IsNotChar::class)]
#[UsesTrait(IsScanner::class)]
final class ParserTest extends TestCase
{
    #[DataProviderExternal(SintacticallyInvalidInputAndParseExpressionProvider::class, 'provide')]
    public function testItThrowsParseExceptionOnLexicallyValidButSyntacticallyInvalidInput(
        string $invalidInput,
        ParserException $parseException,
    ): void {
        $tokens = new Lexer($invalidInput)->tokenize();

        $this->expectExceptionObject($parseException);

        new Parser(...$tokens)->parse();
    }

    #[DataProviderExternal(OperatorPrecedenceProvider::class, 'provide')]
    public function testItParsesInput(string $expectedInput, string $expectedOutput): void
    {
        $tokens = new Lexer($expectedInput)->tokenize();
        $ast = new Parser(...$tokens)->parse();

        $this->assertEquals($expectedOutput, (string) $ast);
    }
}
