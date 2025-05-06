<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer\Scope;

use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;

final readonly class ScopedCollection implements Resolved
{
    private ResolvedCaster $resolvedCaster;
    private ScopeFactory $localScopeFactory;

    /**
     * @var mixed[]
     */
    private array $members;

    /**
     * @param mixed[] $members
     */
    public function __construct(ScopeFactory $localScopeFactory, array $members)
    {
        $this->localScopeFactory = $localScopeFactory;
        $this->members = $members;
        $this->resolvedCaster = new ResolvedCaster();
    }

    public static function type(): ResolvedType
    {
        return ResolvedType::ScopedCollection;
    }

    public function all(Property $variable, DeferredPredicate $predicate): BoolValue
    {
        $all = array_all(
            $this->members,
            fn (mixed $member): bool => $predicate->executeInScope($this->localScopeContext($member, $variable))->bool(),
        );

        return new BoolValue($all);
    }

    public function any(Property $variable, DeferredPredicate $predicate): BoolValue
    {
        $any = array_any(
            $this->members,
            fn (mixed $member): bool => $predicate->executeInScope($this->localScopeContext($member, $variable))->bool(),
        );

        return new BoolValue($any);
    }

    private function localScopeContext(mixed $subject, Property $variable): LocalScopeContext
    {
        return new LocalScopeContext(
            subject: $subject,
            variable: $variable,
            scopeFactory: $this->localScopeFactory
        );
    }

    /**
     * @throws ValueTypeException
     */
    public function fetch(Property $property): ScalarCollection
    {
        return new ScalarCollection(
            ...array_map(
                fn (mixed $member): Scalar => $this->fetchScalar($property, $member),
                $this->members,
            ),
        );
    }

    /**
     * @throws ValueTypeException
     */
    private function fetchScalar(Property $property, mixed $member): Scalar
    {
        $resolved = new RootScopeContext($member, $this->localScopeFactory)->resolve($property);

        return $this->resolvedCaster->scalar($resolved);
    }
}
