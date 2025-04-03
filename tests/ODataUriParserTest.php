<?php

declare(strict_types=1);

namespace Tests;

use olml89\ODataParser\ODataUriParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ODataUriParser::class)]
final class ODataUriParserTest extends TestCase
{
    public function testThis(): void
    {
        $parsed = ODataUriParser::parse('abcde');

        $this->assertContains('abcde', $parsed);
    }
}
