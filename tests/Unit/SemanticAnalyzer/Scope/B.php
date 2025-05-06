<?php

declare(strict_types=1);

namespace Tests\Unit\SemanticAnalyzer\Scope;

final readonly class B
{
    public function __construct(
        public ?string $b = null,
    ) {
    }
}
