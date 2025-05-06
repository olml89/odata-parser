<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\NullableScopeContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullableScopeContext::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(Property::class)]
#[UsesTrait(IsScope::class)]
final class NullableScopeContextTest extends TestCase
{
    public function testItReturnsNullableNullValueIfSubjectIsNull(): void
    {
        $subject = null;
        $property = new Property(new StringValue('a'));
        $nullableScopeContext = new NullableScopeContext(AScope::factory());

        $resolved = $nullableScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
        $this->assertTrue($resolved->nullable());
    }

    public function testItReturnsNullValueIfScopeCannotBeCreatedForSubject(): void
    {
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('a'));
        $nullableScopeContext = new NullableScopeContext(BScope::factory());

        $resolved = $nullableScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
        $this->assertFalse($resolved->nullable());
    }

    public function testItReturnsNullValueIfPropertyCannotBeResolved(): void
    {
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('c'));
        $nullableScopeContext = new NullableScopeContext(AScope::factory());

        $resolved = $nullableScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
        $this->assertFalse($resolved->nullable());
    }

    public function testItReturnsResolvedProperty(): void
    {
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('a'));
        $nullableScopeContext = new NullableScopeContext(AScope::factory());
        $expectedResolved = new StringValue('a');

        $resolved = $nullableScopeContext->resolve($subject, $property);

        $this->assertEquals($expectedResolved, $resolved);
    }
}
