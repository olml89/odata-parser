<?php

declare(strict_types=1);

namespace olml89\ODataParser;

use olml89\ODataParser\Parser\Node\Node;

final readonly class ODataQuery
{
    public function __construct(
        public ?Node $filter,
    ) {
    }
}
