<?php

declare(strict_types=1);

namespace Tests\Unit\Parser;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\TokenOutOfBoundsException;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\Node\Function\IsBinaryFunction;
use olml89\ODataParser\Parser\Node\Function\IsStringFunction;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\IsTernaryFunction;
use olml89\ODataParser\Parser\Node\Function\IsUnaryFunction;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsCollectionLambdaExpression;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\IsUnaryExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\ScalarCaster;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Parser;
use olml89\ODataParser\Parser\TokenManager;
use olml89\ODataParser\Parser\TokenWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Parser\DataProvider\ArithmeticProvider;
use Tests\Unit\Parser\DataProvider\CollectionProvider;
use Tests\Unit\Parser\DataProvider\ComparisonProvider;
use Tests\Unit\Parser\DataProvider\FunctionProvider;
use Tests\Unit\Parser\DataProvider\LogicalProvider;
use Tests\Unit\Parser\DataProvider\PrimaryProvider;
use Tests\Unit\Parser\DataProvider\SubExpressionProvider;

#[CoversClass(Parser::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(ScalarCaster::class)]
#[UsesClass(FloatValue::class)]
#[UsesClass(In::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(Literal::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(Property::class)]
#[UsesClass(TokenManager::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(TokenWrapper::class)]
#[UsesClass(UnexpectedTokenException::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(Substring::class)]
#[UsesClass(TokenOutOfBoundsException::class)]
#[UsesTrait(IsBinaryExpression::class)]
#[UsesTrait(IsBinaryFunction::class)]
#[UsesTrait(IsCollectionLambdaExpression::class)]
#[UsesTrait(IsStringFunction::class)]
#[UsesTrait(IsTernaryFunction::class)]
#[UsesTrait(IsUnaryFunction::class)]
#[UsesTrait(IsUnaryExpression::class)]
final class ParserTest extends TestCase
{
    public function testItParsesEmptyTokensAsNull(): void
    {
        $parser = new Parser();

        $this->assertNull($parser->parse());
    }

    public function testItDoesNotAllowInvalidTokens(): void
    {
        $invalidToken = new OperatorToken(TokenKind::Function);

        $this->expectExceptionObject(
            UnexpectedTokenException::position($invalidToken, position: 0),
        );

        new Parser($invalidToken)->parse();
    }

    #[DataProviderExternal(ComparisonProvider::class, 'provide')]
    #[DataProviderExternal(ArithmeticProvider::class, 'provide')]
    #[DataProviderExternal(LogicalProvider::class, 'provide')]
    #[DataProviderExternal(CollectionProvider::class, 'provide')]
    #[DataProviderExternal(FunctionProvider::class, 'provide')]
    #[DataProviderExternal(PrimaryProvider::class, 'provide')]
    public function testItParsesSimpleTokens(Node $expectedNode, Token ...$tokens): void
    {
        $parser = new Parser(...$tokens);

        $this->assertEquals($expectedNode, $parser->parse());
    }

    #[DataProviderExternal(SubExpressionProvider::class, 'provide')]
    public function testItParsesSubExpressions(Node $expectedNode, Token ...$tokens): void
    {
        $parser = new Parser(...$tokens);

        $this->assertEquals($expectedNode, $parser->parse());
    }
}
