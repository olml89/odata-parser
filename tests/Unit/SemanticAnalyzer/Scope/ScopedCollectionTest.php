<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\IsBinaryExpression;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\IsNullable;
use olml89\ODataParser\Parser\Node\Value\IsValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\DeferredResolver;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\EqualHandler;
use olml89\ODataParser\SemanticAnalyzer\Handler\Expression\Comparison\NotEqualHandler;
use olml89\ODataParser\SemanticAnalyzer\PredicateBuilderVisitor;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredResolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ResolvedCaster;
use olml89\ODataParser\SemanticAnalyzer\Scope\RootScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScopedCollection::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(DeferredPredicate::class)]
#[UsesClass(DeferredResolved::class)]
#[UsesClass(DeferredResolver::class)]
#[UsesClass(EqualHandler::class)]
#[UsesClass(Literal::class)]
#[UsesClass(LocalScopeContext::class)]
#[UsesClass(NotEqualHandler::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(PredicateBuilderVisitor::class)]
#[UsesClass(Property::class)]
#[UsesClass(ResolvedCaster::class)]
#[UsesClass(RootScopeContext::class)]
#[UsesClass(ScalarCollection::class)]
#[UsesClass(ScopeManager::class)]
#[UsesClass(StringValue::class)]
#[UsesTrait(IsBinaryExpression::class)]
#[UsesTrait(IsNullable::class)]
#[UsesTrait(IsScope::class)]
#[UsesTrait(IsValue::class)]
final class ScopedCollectionTest extends TestCase
{
    private ScopedCollection $collectionWithNull;
    private ScopedCollection $notNullCollection;
    private Property $variable;

    protected function setUp(): void
    {
        $this->collectionWithNull = new ScopedCollection(
            BScope::factory(),
            [
                new B(b: 'b'),
                new B(b: 'bb'),
                new B(b: 'bbb'),
                new B(b: null),
            ],
        );

        $this->notNullCollection = new ScopedCollection(
            BScope::factory(),
            [
                new B(b: 'b'),
                new B(b: 'bb'),
                new B(b: 'bbb'),
            ],
        );

        $this->variable = new Property(new StringValue('b'));
    }

    public function testAll(): void
    {
        $predicate = new DeferredPredicate(
            handler: new NotEqualHandler(
                new NotEqual(
                    left: new Property(new StringValue('b')),
                    right: new Literal(new NullValue()),
                )
            ),
            visitor: new PredicateBuilderVisitor(AScope::factory()),
        );

        $this->assertFalse($this->collectionWithNull->all($this->variable, $predicate)->bool());
        $this->assertTrue($this->notNullCollection->all($this->variable, $predicate)->bool());
    }

    public function testAny(): void
    {
        $predicate = new DeferredPredicate(
            handler: new EqualHandler(
                new Equal(
                    left: new Property(new StringValue('b')),
                    right: new Literal(new NullValue()),
                )
            ),
            visitor: new PredicateBuilderVisitor(AScope::factory()),
        );

        $this->assertTrue($this->collectionWithNull->any($this->variable, $predicate)->bool());
        $this->assertFalse($this->notNullCollection->any($this->variable, $predicate)->bool());
    }

    public function testFetch(): void
    {
        $this->assertEquals(
            new ScalarCollection(
                new StringValue('b'),
                new StringValue('bb'),
                new StringValue('bbb'),
                new NullValue(nullable: true),
            ),
            $this->collectionWithNull->fetch($this->variable),
        );
        $this->assertEquals(
            new ScalarCollection(
                new StringValue('b'),
                new StringValue('bb'),
                new StringValue('bbb'),
            ),
            $this->notNullCollection->fetch($this->variable),
        );
    }
}
