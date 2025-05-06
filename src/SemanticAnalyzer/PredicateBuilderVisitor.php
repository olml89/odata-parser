<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer;

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
use olml89\ODataParser\Parser\Node\Expression\Comparison\Has;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
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
use olml89\ODataParser\Parser\Node\Value\Value;
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
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredInt;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredNumber;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredString;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredResolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeManager;

final readonly class PredicateBuilderVisitor implements Visitor
{
    private ScopeManager $scopeManager;

    public function __construct(ScopeFactory $rootScopeFactory)
    {
        $this->scopeManager = new ScopeManager($rootScopeFactory);
    }

    public function executeInScope(LocalScopeContext $localScopeContext, DeferredPredicate $predicate): BoolValue
    {
        return $this->scopeManager->executeInScope($localScopeContext, $predicate);
    }

    public function visitConcat(Concat $concat): DeferredString
    {
        return new DeferredString(new ConcatHandler($concat), $this);
    }

    public function visitContains(Contains $contains): DeferredPredicate
    {
        return new DeferredPredicate(new ContainsHandler($contains), $this);
    }

    public function visitEndsWith(EndsWith $endsWith): DeferredPredicate
    {
        return new DeferredPredicate(new EndsWithHandler($endsWith), $this);
    }

    public function visitIndexOf(IndexOf $indexOf): DeferredInt
    {
        return new DeferredInt(new IndexOfHandler($indexOf), $this);
    }

    public function visitLength(Length $length): DeferredInt
    {
        return new DeferredInt(new LengthHandler($length), $this);
    }

    public function visitMatchesPattern(MatchesPattern $matchesPattern): DeferredPredicate
    {
        return new DeferredPredicate(new MatchesPatternHandler($matchesPattern), $this);
    }

    public function visitStartsWith(StartsWith $startsWith): DeferredPredicate
    {
        return new DeferredPredicate(new StartsWithHandler($startsWith), $this);
    }

    public function visitSubstring(Substring $substring): DeferredString
    {
        return new DeferredString(new SubstringHandler($substring), $this);
    }

    public function visitToLower(ToLower $toLower): DeferredString
    {
        return new DeferredString(new ToLowerHandler($toLower), $this);
    }

    public function visitToUpper(ToUpper $toUpper): DeferredString
    {
        return new DeferredString(new ToUpperHandler($toUpper), $this);
    }

    public function visitTrim(Trim $trim): DeferredString
    {
        return new DeferredString(new TrimHandler($trim), $this);
    }

    public function visitAdd(Add $add): DeferredNumber
    {
        return new DeferredNumber(new AddHandler($add), $this);
    }

    public function visitDiv(Div $div): DeferredNumber
    {
        return new DeferredNumber(new DivHandler($div), $this);
    }

    public function visitDivBy(DivBy $divBy): DeferredNumber
    {
        return new DeferredNumber(new DivByHandler($divBy), $this);
    }

    public function visitMinus(Minus $minus): DeferredNumber
    {
        return new DeferredNumber(new MinusHandler($minus), $this);
    }

    public function visitMod(Mod $mod): DeferredNumber
    {
        return new DeferredNumber(new ModHandler($mod), $this);
    }

    public function visitMul(Mul $mul): DeferredNumber
    {
        return new DeferredNumber(new MulHandler($mul), $this);
    }

    public function visitSub(Sub $sub): DeferredNumber
    {
        return new DeferredNumber(new SubHandler($sub), $this);
    }

    public function visitAll(All $all): DeferredPredicate
    {
        return new DeferredPredicate(new AllHandler($all), $this);
    }

    public function visitAny(Any $any): DeferredPredicate
    {
        return new DeferredPredicate(new AnyHandler($any), $this);
    }

    public function visitEqual(Equal $equal): DeferredPredicate
    {
        return new DeferredPredicate(new EqualHandler($equal), $this);
    }

    public function visitGreaterThan(GreaterThan $greaterThan): DeferredPredicate
    {
        return new DeferredPredicate(new GreaterThanHandler($greaterThan), $this);
    }

    public function visitGreaterThanOrEqual(GreaterThanOrEqual $greaterThanOrEqual): DeferredPredicate
    {
        return new DeferredPredicate(new GreaterThanOrEqualHandler($greaterThanOrEqual), $this);
    }

    public function visitHas(Has $has): mixed
    {
        return null;
    }

    public function visitIn(In $in): DeferredPredicate
    {
        return new DeferredPredicate(new InHandler($in), $this);
    }

    public function visitLessThan(LessThan $lessThan): DeferredPredicate
    {
        return new DeferredPredicate(new LessThanHandler($lessThan), $this);
    }

    public function visitLessThanOrEqual(LessThanOrEqual $lessThanOrEqual): DeferredPredicate
    {
        return new DeferredPredicate(new LessThanOrEqualHandler($lessThanOrEqual), $this);
    }

    public function visitNotEqual(NotEqual $notEqual): DeferredPredicate
    {
        return new DeferredPredicate(new NotEqualHandler($notEqual), $this);
    }

    public function visitAnd(AndExpression $andExpression): DeferredPredicate
    {
        return new DeferredPredicate(new AndHandler($andExpression), $this);
    }

    public function visitNot(NotExpression $notExpression): DeferredPredicate
    {
        return new DeferredPredicate(new NotHandler($notExpression), $this);
    }

    public function visitOr(OrExpression $orExpression): DeferredPredicate
    {
        return new DeferredPredicate(new OrHandler($orExpression), $this);
    }

    public function visitLiteral(Literal $literal): Value
    {
        return $literal->value;
    }

    public function visitProperty(Property $property): DeferredResolved
    {
        return new DeferredResolved($property, $this->scopeManager);
    }
}
