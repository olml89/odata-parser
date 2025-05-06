<?php

declare(strict_types=1);

namespace Tests\Helper\City;

use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\SemanticAnalyzer\Exception\InvalidAstException;
use olml89\ODataParser\SemanticAnalyzer\Scope\Deferred\DeferredPredicate;
use olml89\ODataParser\SemanticAnalyzer\Visitor;
use Tests\Helper\City\Specification\CitySpecification;
use Tests\Helper\City\Specification\DynamicCitySpecification;

final readonly class CitySpecificationBuilder
{
    public function __construct(
        private Visitor $visitor,
    ) {
    }

    /**
     * @throws InvalidAstException
     */
    public function build(?Node $ast): ?CitySpecification
    {
        if (is_null($ast)) {
            return null;
        }

        $result = $ast->accept($this->visitor);

        if (!($result instanceof DeferredPredicate)) {
            throw new InvalidAstException($ast);
        }

        return new DynamicCitySpecification($result);
    }
}
