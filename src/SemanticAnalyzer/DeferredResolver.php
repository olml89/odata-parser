<?php

declare(strict_types=1);

namespace olml89\ODataParser\SemanticAnalyzer;

use olml89\ODataParser\Parser\Exception\NodeTypeException;
use olml89\ODataParser\Parser\Exception\ValueTypeException;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\NodeType;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\BoolValue;
use olml89\ODataParser\Parser\Node\Value\FloatValue;
use olml89\ODataParser\Parser\Node\Value\IntValue;
use olml89\ODataParser\Parser\Node\Value\NullValue;
use olml89\ODataParser\Parser\Node\Value\Number;
use olml89\ODataParser\Parser\Node\Value\Scalar;
use olml89\ODataParser\Parser\Node\Value\ScalarCollection;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\Parser\Node\Value\Value;
use olml89\ODataParser\Parser\Node\Value\ValueType;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\Deferred;
use olml89\ODataParser\SemanticAnalyzer\Scope\ScopedCollection;
use olml89\ODataParser\SemanticAnalyzer\Scope\Resolved;
use olml89\ODataParser\SemanticAnalyzer\Scope\ResolvedCaster;

final readonly class DeferredResolver
{
    private ResolvedCaster $resolvedCaster;

    public function __construct(
        private mixed $subject,
    ) {
        $this->resolvedCaster = new ResolvedCaster();
    }

    public function resolve(Node $node, PredicateBuilderVisitor $visitor): Resolved
    {
        /** @var Resolved|Deferred $execution */
        $execution = $node->accept($visitor);

        return $execution instanceof Resolved ? $execution : $execution->fetch($this->subject);
    }

    /**
     * @throws NodeTypeException
     * @throws ValueTypeException
     */
    public function value(Node $node, PredicateBuilderVisitor $visitor): Value
    {
        $resolved = $this->resolve($node, $visitor);
        $result = $this->resolvedCaster->tryScalar($resolved) ?? $this->resolvedCaster->tryScopedCollection($resolved);

        if (is_null($result)) {
            throw new ValueTypeException(
                $result,
                ...ValueType::cases(),
            );
        }

        if ($result instanceof Scalar) {
            return $result;
        }

        if (!($node instanceof Property)) {
            throw new NodeTypeException($node, expectedTypes: NodeType::Property);
        }

        return $result->fetch($node);
    }

    /**
     * @throws ValueTypeException
     */
    public function scalar(Node $node, PredicateBuilderVisitor $visitor): Scalar
    {
        return $this->resolvedCaster->scalar($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     */
    public function string(Node $node, PredicateBuilderVisitor $visitor): StringValue
    {
        return $this->resolvedCaster->string($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     */
    public function bool(Node $node, PredicateBuilderVisitor $visitor): BoolValue
    {
        return $this->resolvedCaster->bool($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     */
    public function int(Node $node, PredicateBuilderVisitor $visitor): IntValue
    {
        return $this->resolvedCaster->int($this->resolve($node, $visitor));
    }

    public function tryInt(Node $node, PredicateBuilderVisitor $visitor): IntValue|NullValue
    {
        return $this->resolvedCaster->tryInt($this->resolve($node, $visitor)) ?? new NullValue();
    }

    /**
     * @throws ValueTypeException
     */
    public function float(Node $node, PredicateBuilderVisitor $visitor): FloatValue
    {
        return $this->resolvedCaster->float($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     */
    public function number(Node $node, PredicateBuilderVisitor $visitor): Number
    {
        return $this->resolvedCaster->number($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     */
    public function scopedCollection(Node $node, PredicateBuilderVisitor $visitor): ScopedCollection
    {
        return $this->resolvedCaster->scopedCollection($this->resolve($node, $visitor));
    }

    /**
     * @throws ValueTypeException
     * @throws NodeTypeException
     */
    public function container(Node $node, PredicateBuilderVisitor $visitor): StringValue|ScalarCollection
    {
        $resolved = $this->resolve($node, $visitor);
        $result = $this->resolvedCaster->tryString($resolved) ?? $this->resolvedCaster->tryScopedCollection($resolved);

        if (is_null($result)) {
            throw new ValueTypeException(
                $result,
                ValueType::String,
                ValueType::ScalarCollection,
            );
        }

        if ($result instanceof StringValue) {
            return $result;
        }

        if (!($node instanceof Property)) {
            throw new NodeTypeException($node, expectedTypes: NodeType::Property);
        }

        return $result->fetch($node);
    }
}
