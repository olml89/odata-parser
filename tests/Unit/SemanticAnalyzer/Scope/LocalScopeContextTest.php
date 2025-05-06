<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\IsValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\NullableScopeContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocalScopeContext::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(Property::class)]
#[UsesClass(NullableScopeContext::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(StringValue::class)]
#[UsesTrait(IsScope::class)]
#[UsesTrait(IsValue::class)]
final class LocalScopeContextTest extends TestCase
{
    /**
     * @return array<string, list<mixed>>
     */
    public static function provideSubjectAndScopeSubject(): array
    {
        return [
            'subject: object, scope subject: scalar' => [
                new A(a: 'a'),
                'abcde',
            ],
            'subject: object, scope subject: object of different class' => [
                new A(a: 'a'),
                new B(b: 'b'),
            ],
            'subject: scalar, scope subject: object' => [
                'abcde',
                new A(a: 'a'),
                new Property(new StringValue('a')),
            ],
            'subject: scalar, scope scalar of different type' => [
                'abcde',
                12,
            ],
        ];
    }

    #[DataProvider('provideSubjectAndScopeSubject')]
    public function testItReturnsNullValueIfSubjectDoesNotMatchScopeSubjectType(
        mixed $subject,
        mixed $scopeSubject,
    ): void {
        $property = new Property(new StringValue('a'));

        $localScopeContext = new LocalScopeContext(
            subject: $scopeSubject,
            variable: new Property(new StringValue('a')),
            scopeFactory: AScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsNullValueIfPropertyDoesNotMatchLocalVariable(): void
    {
        /**
         * AScope
         * a -> $subject->a -> 'a'
         */
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('a'));

        $localScopeContext = new LocalScopeContext(
            subject: $subject,
            variable: new Property(new StringValue('b')),
            scopeFactory: AScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsNullValueIfScopeCannotBeCreatedForSubject(): void
    {
        /**
         * AScope
         * a -> $subject->a -> 'a'
         */
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('a'));

        $localScopeContext = new LocalScopeContext(
            subject: $subject,
            variable: new Property(new StringValue('a')),
            scopeFactory: BScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsNullValueIfPropertyCannotBeResolved(): void
    {
        /**
         * AScope
         * a -> $subject->a -> 'a'
         */
        $subject = new A(a: 'a');
        $property = new Property(new StringValue('c'));

        $localScopeContext = new LocalScopeContext(
            subject: $subject,
            variable: new Property(new StringValue('a')),
            scopeFactory: AScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertInstanceOf(NullValue::class, $resolved);
    }

    public function testItReturnsResolvedProperty(): void
    {
        /**
         * AScope
         * a -> $subject->a -> 'a'
         */
        $subject = new A(a: 'a');
        $scopeSubject = new A(a: 'aa');
        $property = new Property(new StringValue('a'));
        $expectedResolved = new StringValue('aa');

        $localScopeContext = new LocalScopeContext(
            subject: $scopeSubject,
            variable: new Property(new StringValue('a')),
            scopeFactory: AScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertEquals($expectedResolved, $resolved);
    }

    public function testItReturnsResolvedSubProperty(): void
    {
        /**
         * AScope
         * a/b -> $subject->a->b -> 'b'
         */
        $subject = new A(a: null);

        $scopeSubject = new A(
            a: 'a',
            b: new B(b: 'b'),
        );

        $property = new Property(
            new StringValue('a'),
            subProperty: new Property(new StringValue('b')),
        );

        $expectedResolved = new StringValue('b');

        $localScopeContext = new LocalScopeContext(
            subject: $scopeSubject,
            variable: new Property(new StringValue('a')),
            scopeFactory: AScope::factory(),
        );

        $resolved = $localScopeContext->resolve($subject, $property);

        $this->assertEquals($expectedResolved, $resolved);
    }
}
