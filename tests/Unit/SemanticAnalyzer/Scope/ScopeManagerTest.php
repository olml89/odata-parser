<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\IsValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Exception\UnknownPropertyException;
use olml89\ODataParser\SemanticAnalyzer\Scope\IsScope;
use olml89\ODataParser\SemanticAnalyzer\Scope\LocalScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\RootScopeContext;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeFactory;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopeManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(ScopeManager::class)]
#[UsesClass(BoolValue::class)]
#[UsesClass(LocalScopeContext::class)]
#[UsesClass(NullValue::class)]
#[UsesClass(Property::class)]
#[UsesClass(RootScopeContext::class)]
#[UsesClass(StringValue::class)]
#[UsesClass(UnknownPropertyException::class)]
#[UsesTrait(IsScope::class)]
#[UsesTrait(IsValue::class)]
final class ScopeManagerTest extends TestCase
{
    private static function createScopeManager(
        ScopeFactory $rootScopeFactory,
        LocalScopeContext ...$localScopeContextsStack,
    ): ScopeManager {
        $scopeManagerClass = new ReflectionClass(ScopeManager::class);
        $scopeManager = $scopeManagerClass->newInstance($rootScopeFactory);

        $localScopeContextsStackProperty = $scopeManagerClass->getProperty('localScopeContextsStack');
        $localScopeContextsStackProperty->setValue($scopeManager, $localScopeContextsStack);

        return $scopeManager;
    }

    /**
     * @return array<string, array{0: A|B|C, 1: Property, 2: ScopeManager, 3: UnknownPropertyException}>
     */
    public static function provideSubjectAndPropertyAndScopeManagerAndExpectedUnknownPropertyException(): array
    {
        return [
            'root context: subject not matching root scope type' => [
                $subject = new A(a: 'a'),
                $property = new Property(new StringValue('a')),
                self::createScopeManager(BScope::factory()),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            'root context: property existing in root scope but subject not matching root scope type' => [
                $subject = new A(a: 'a'),
                $property = new Property(new StringValue('b')),
                self::createScopeManager(BScope::factory()),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            'root context: unknown property' => [
                $subject = new A(a: 'a'),
                $property = new Property(new StringValue('c')),
                self::createScopeManager(AScope::factory()),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            'local context: subject not matching local scope type' => [
                $subject = new C(c: 'c'),
                $property = new Property(new StringValue('c')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('c')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'a'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            // A::b() does exist, but AScope is not in the ScopeManager
            'local context: property existing in local scope but subject not matching local scope type' => [
                $subject = new A(a: 'a'),
                $property = new Property(new StringValue('b')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(CScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new C(c: 'c'), new Property(new StringValue('c')));

                    return $scopeManager;
                })(),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            'local context: property not matching variable' => [
                $subject = new C(c: 'c'),
                $property = new Property(new StringValue('c')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'a'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
            'local context: unknown property' => [
                $subject = new B(b: 'b'),
                $property = new Property(new StringValue('c')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'a'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new UnknownPropertyException(subject: $subject, property: $property),
            ],
        ];
    }

    #[DataProvider('provideSubjectAndPropertyAndScopeManagerAndExpectedUnknownPropertyException')]
    public function testItThrowsUnknownPropertyExceptionIfItCannotResolveFromTheCorrectScope(
        mixed $subject,
        Property $property,
        ScopeManager $scopeManager,
        UnknownPropertyException $expectedException,
    ): void {
        $this->expectExceptionObject($expectedException);

        $scopeManager->resolve($subject, $property);
    }

    /**
     * @return array<string, array{0: A|B, 1: Property, 2: ScopeManager, 3: Scalar}>
     */
    public static function provideSubjectAndPropertyAndScopeManagerAndExpectedResolved(): array
    {
        return [
            'root context' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                self::createScopeManager(AScope::factory()),
                new StringValue('a'),
            ],
            'root context (resolved nullable NullValue)' => [
                new A(a: null),
                new Property(new StringValue('a')),
                self::createScopeManager(AScope::factory()),
                new NullValue(nullable: true),
            ],
            'root context: previous root context, no local context' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $scopeManager = self::createScopeManager(AScope::factory());

                    // Create RootContext
                    $scopeManager->resolve(new A(a: 'aa'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new StringValue('a'),
            ],
            'root context: previous root context, no local context (resolved nullable NullValue)' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $scopeManager = self::createScopeManager(AScope::factory());

                    // Create RootContext
                    $scopeManager->resolve(new A(a: null), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new StringValue('a'),
            ],
            'root context: no previous root context, local context' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    return self::createScopeManager(AScope::factory(), $localScopeContext);
                })(),
                new StringValue('a'),
            ],
            'root context: no previous root context, local context (resolved nullable NullValue)' => [
                new A(a: null),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    return self::createScopeManager(AScope::factory(), $localScopeContext);
                })(),
                new NullValue(nullable: true),
            ],
            'root context: previous root context, local context' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'aa'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new StringValue('aa'),
            ],
            'root context: previous root context, local context (resolved nullable NullValue)' => [
                new A(a: 'a'),
                new Property(new StringValue('a')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'b'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: null), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new NullValue(nullable: true),
            ],
            'local context: no previous root context' => [
                new B(b: 'b'),
                new Property(new StringValue('b')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'bb'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    return self::createScopeManager(AScope::factory(), $localScopeContext);
                })(),
                new StringValue('bb'),
            ],
            'local context: no previous root context (resolved nullable NullValue)' => [
                new B(b: 'b'),
                new Property(new StringValue('b')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: null),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    return self::createScopeManager(AScope::factory(), $localScopeContext);
                })(),
                new NullValue(nullable: true),
            ],
            'local context: previous root context' => [
                new B(b: 'b'),
                new Property(new StringValue('b')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: 'bb'),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'a'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new StringValue('bb'),
            ],
            'local context: previous root context (resolved nullable NullValue)' => [
                new B(b: 'b'),
                new Property(new StringValue('b')),
                (function (): ScopeManager {
                    $localScopeContext = new LocalScopeContext(
                        subject: new B(b: null),
                        variable: new Property(new StringValue('b')),
                        scopeFactory: BScope::factory(),
                    );

                    // Create RootScopeContext
                    $scopeManager = self::createScopeManager(AScope::factory(), $localScopeContext);
                    $scopeManager->resolve(new A(a: 'a'), new Property(new StringValue('a')));

                    return $scopeManager;
                })(),
                new NullValue(nullable: true),
            ],
        ];
    }

    #[DataProvider('provideSubjectAndPropertyAndScopeManagerAndExpectedResolved')]
    public function testItResolvesFromTheCorrectScope(
        mixed $subject,
        Property $property,
        ScopeManager $scopeManager,
        Resolved $expectedResolved,
    ): void {
        $resolved = $scopeManager->resolve($subject, $property);

        $this->assertEquals($expectedResolved, $resolved);
    }
}
