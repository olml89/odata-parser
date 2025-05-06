<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\RootScopeContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(RootScopeContext::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(Property::class)]
#[UsesClass(StringValue::class)]
#[UsesTrait(IsScope::class)]
final class RootScopeContextTest extends TestCase
{
    public function testItReturnsNullValueIfScopeCannotBeCreatedForSubject(): void
    {
        $property = new Property(new StringValue('b'));

        $rootScopeContext = new RootScopeContext(
            subject: new B(b: 'b'),
            scopeFactory: AScope::factory(),
        );

        $resolved = $rootScopeContext->resolve($property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsNullValueIfPropertyCannotBeResolved(): void
    {
        $property = new Property(new StringValue('c'));

        $rootScopeContext = new RootScopeContext(
            subject: new A(a: 'a'),
            scopeFactory: AScope::factory(),
        );

        $resolved = $rootScopeContext->resolve($property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsResolvedProperty(): void
    {
        $property = new Property(new StringValue('a'));
        $expectedResolved = new StringValue('a');

        $rootScopeContext = new RootScopeContext(
            subject: new A(a: 'a'),
            scopeFactory: AScope::factory(),
        );

        $resolved = $rootScopeContext->resolve($property);

        $this->assertEquals($expectedResolved, $resolved);
    }
}
