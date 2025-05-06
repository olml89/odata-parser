<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

final readonly class A
{
    public function __construct(
        public ?string $a = null,
        public ?B $b = null,
    ) {
    }
}
