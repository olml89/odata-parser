<?php

declare(strict_types=1);

namespace Tests\Integration\SemanticAnalyzer;

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
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Expression\Comparison\All;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\Parser\Node\Expression\IsUnaryExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\HasMultipleOperands;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Function\IsBinaryFunction;
use olml89\ODataParser\Parser\Node\Function\IsStringFunction;
use olml89\ODataParser\Parser\Node\Function\IsTernaryFunction;
use olml89\ODataParser\Parser\Node\Function\IsUnaryFunction;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\ToUpper;
use olml89\ODataParser\Parser\Node\Function\Trim;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\ScalarCaster;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Parser;
use olml89\ODataParser\Parser\TokenManager;
use olml89\ODataParser\Parser\TokenWrapper;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\AddHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\DivByHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\DivHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\MinusHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\ModHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\MulHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Arithmetic\SubHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\AllHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\AnyHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\EqualHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\GreaterThanHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\GreaterThanOrEqualHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\InHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\LessThanHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\LessThanOrEqualHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\NotEqualHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Logical\AndHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Logical\NotHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Logical\OrHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\ConcatHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\ContainsHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\EndsWithHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\IndexOfHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\LengthHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\MatchesPatternHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\StartsWithHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\SubstringHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\ToLowerHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\ToUpperHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Function\TrimHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredInt;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredNumber;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredResolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredString;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ResolvedCaster;
use olml89\ODataParser\SemanticAnalyzer\Scope\RootScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Tests\Helper\City\CityRepository;
use Tests\Helper\City\CityRepositoryAndQueryAndExpectedResultsProvider;
use Tests\Helper\City\CitySpecificationBuilder;
use Tests\Helper\City\Entity\City;
use Tests\Helper\City\Scope\CityScope;

#[CoversClass(PredicateBuilderVisitor::class)]
#[UsesClass(Add::class)]
#[UsesClass(AddHandler::class)]
#[UsesClass(All::class)]
#[UsesClass(AllHandler::class)]
#[UsesClass(AndExpression::class)]
#[UsesClass(AndHandler::class)]
#[UsesClass(Any::class)]
#[UsesClass(AnyHandler::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(ScalarCaster::class)]
#[UsesClass(Char::class)]
#[UsesClass(Concat::class)]
#[UsesClass(ConcatHandler::class)]
#[UsesClass(Contains::class)]
#[UsesClass(ContainsHandler::class)]
#[UsesClass(ScopedCollection::class)]
#[UsesClass(DeferredInt::class)]
#[UsesClass(DeferredNumber::class)]
#[UsesClass(DeferredPredicate::class)]
#[UsesClass(DeferredResolved::class)]
#[UsesClass(DeferredResolver::class)]
#[UsesClass(DeferredString::class)]
#[UsesClass(Div::class)]
#[UsesClass(DivBy::class)]
#[UsesClass(DivByHandler::class)]
#[UsesClass(DivHandler::class)]
#[UsesClass(EndsWith::class)]
#[UsesClass(EndsWithHandler::class)]
#[UsesClass(Equal::class)]
#[UsesClass(EqualHandler::class)]
#[UsesClass(FloatValue::class)]
#[UsesClass(GreaterThan::class)]
#[UsesClass(GreaterThanHandler::class)]
#[UsesClass(GreaterThanOrEqual::class)]
#[UsesClass(GreaterThanOrEqualHandler::class)]
#[UsesClass(IdentifierScanner::class)]
#[UsesClass(In::class)]
#[UsesClass(IndexOf::class)]
#[UsesClass(IndexOfHandler::class)]
#[UsesClass(InHandler::class)]
#[UsesClass(IntValue::class)]
#[UsesClass(KeywordScanner::class)]
#[UsesClass(LessThan::class)]
#[UsesClass(LessThanHandler::class)]
#[UsesClass(LessThanOrEqual::class)]
#[UsesClass(LessThanOrEqualHandler::class)]
#[UsesClass(Lexer::class)]
#[UsesClass(Literal::class)]
#[UsesClass(Length::class)]
#[UsesClass(LengthHandler::class)]
#[UsesClass(LocalScopeContext::class)]
#[UsesClass(MatchesPattern::class)]
#[UsesClass(MatchesPatternHandler::class)]
#[UsesClass(Minus::class)]
#[UsesClass(MinusHandler::class)]
#[UsesClass(Mul::class)]
#[UsesClass(MulHandler::class)]
#[UsesClass(Mod::class)]
#[UsesClass(ModHandler::class)]
#[UsesClass(NotEqual::class)]
#[UsesClass(NotEqualHandler::class)]
#[UsesClass(NotExpression::class)]
#[UsesClass(NotHandler::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(NumericScanner::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(OrExpression::class)]
#[UsesClass(OrHandler::class)]
#[UsesClass(Parser::class)]
#[UsesClass(Property::class)]
#[UsesClass(ResolvedCaster::class)]
#[UsesClass(RootScopeContext::class)]
#[UsesClass(ScalarCollection::class)]
#[UsesClass(ScannerPipeline::class)]
#[UsesClass(ScopeManager::class)]
#[UsesClass(Source::class)]
#[UsesClass(SpecialChar::class)]
#[UsesClass(SpecialCharScanner::class)]
#[UsesClass(StartsWith::class)]
#[UsesClass(StartsWithHandler::class)]
#[UsesClass(StringScanner::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(Sub::class)]
#[UsesClass(SubHandler::class)]
#[UsesClass(Substring::class)]
#[UsesClass(SubstringHandler::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(TokenManager::class)]
#[UsesClass(TokenWrapper::class)]
#[UsesClass(ToLower::class)]
#[UsesClass(ToLowerHandler::class)]
#[UsesClass(ToUpper::class)]
#[UsesClass(ToUpperHandler::class)]
#[UsesClass(Trim::class)]
#[UsesClass(TrimHandler::class)]
#[UsesClass(ValueToken::class)]
#[UsesTrait(HasMultipleOperands::class)]
#[UsesTrait(IsBinaryExpression::class)]
#[UsesTrait(IsBinaryFunction::class)]
#[UsesTrait(IsNotChar::class)]
#[UsesTrait(IsScanner::class)]
#[UsesTrait(IsScope::class)]
#[UsesTrait(IsStringFunction::class)]
#[UsesTrait(IsTernaryFunction::class)]
#[UsesTrait(IsUnaryExpression::class)]
#[UsesTrait(IsUnaryFunction::class)]
final class PredicateBuilderVisitorTest extends TestCase
{
    #[DataProviderExternal(CityRepositoryAndQueryAndExpectedResultsProvider::class, 'provide')]
    public function testAstSemanticTransformation(CityRepository $cities, string $query, City ...$expectedCities): void
    {
        $tokens = new Lexer($query)->tokenize();
        $ast = new Parser(...$tokens)->parse();

        $citySpecificationBuilder = new CitySpecificationBuilder(
            new PredicateBuilderVisitor(CityScope::factory())
        );

        $citySpecification = $citySpecificationBuilder->build($ast);

        $filteredCities = $cities->find($citySpecification);

        $this->assertEquals($expectedCities, $filteredCities);
    }
}
