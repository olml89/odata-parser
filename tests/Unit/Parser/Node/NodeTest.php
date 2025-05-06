<?php

declare(strict_types=1);

namespace Tests\Unit\Parser\Node;

use olml89\ODataParser\Parser\Node\Function\Concat;
use olml89\ODataParser\Parser\Node\Function\Contains;
use olml89\ODataParser\Parser\Node\Function\EndsWith;
use olml89\ODataParser\Parser\Node\Function\IndexOf;
use olml89\ODataParser\Parser\Node\Function\Length;
use olml89\ODataParser\Parser\Node\Function\MatchesPattern;
use olml89\ODataParser\Parser\Node\Function\StartsWith;
use olml89\ODataParser\Parser\Node\Function\Substring;
use olml89\ODataParser\Parser\Node\Function\ToLower;
use olml89\ODataParser\Parser\Node\Function\ToUpper;
use olml89\ODataParser\Parser\Node\Function\Trim;
use olml89\ODataParser\Parser\Node\Literal;
use olml89\ODataParser\Parser\Node\Node;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Add;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Div;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\DivBy;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Minus;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mod;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Mul;
use olml89\ODataParser\Parser\Node\Expression\Arithmetic\Sub;
use olml89\ODataParser\Parser\Node\Expression\Comparison\All;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Any;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Equal;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\GreaterThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\Has;
use olml89\ODataParser\Parser\Node\Expression\Comparison\In;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThan;
use olml89\ODataParser\Parser\Node\Expression\Comparison\LessThanOrEqual;
use olml89\ODataParser\Parser\Node\Expression\Comparison\NotEqual;
use olml89\ODataParser\Parser\Node\Expression\Logical\AndExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\NotExpression;
use olml89\ODataParser\Parser\Node\Expression\Logical\OrExpression;
use olml89\ODataParser\Parser\Node\Property;
use olml89\ODataParser\Parser\Node\Value\StringValue;
use olml89\ODataParser\SemanticAnalyzer\Visitor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Parser\Node\DataProvider\NodeAndSerializationProvider;
use Tests\Unit\Parser\Node\DataProvider\NodeAndVisitorMethodProvider;

#[CoversClass(Add::class)]
#[CoversClass(All::class)]
#[CoversClass(AndExpression::class)]
#[CoversClass(Any::class)]
#[CoversClass(Concat::class)]
#[CoversClass(Contains::class)]
#[CoversClass(Div::class)]
#[CoversClass(DivBy::class)]
#[CoversClass(EndsWith::class)]
#[CoversClass(Equal::class)]
#[CoversClass(GreaterThan::class)]
#[CoversClass(GreaterThanOrEqual::class)]
#[CoversClass(Has::class)]
#[CoversClass(In::class)]
#[CoversClass(IndexOf::class)]
#[CoversClass(Length::class)]
#[CoversClass(LessThan::class)]
#[CoversClass(LessThanOrEqual::class)]
#[CoversClass(Literal::class)]
#[CoversClass(MatchesPattern::class)]
#[CoversClass(Minus::class)]
#[CoversClass(Mod::class)]
#[CoversClass(Mul::class)]
#[CoversClass(NotEqual::class)]
#[CoversClass(NotExpression::class)]
#[CoversClass(OrExpression::class)]
#[CoversClass(Property::class)]
#[CoversClass(StartsWith::class)]
#[CoversClass(Sub::class)]
#[CoversClass(Substring::class)]
#[CoversClass(ToLower::class)]
#[CoversClass(ToUpper::class)]
#[CoversClass(Trim::class)]
#[UsesClass(StringValue::class)]
final class NodeTest extends TestCase
{
    #[DataProviderExternal(NodeAndVisitorMethodProvider::class, 'provide')]
    public function testAccepts(Node $node, string $visitorMethod): void
    {
        $visitor = $this->createMock(Visitor::class);

        /** @var non-empty-string $visitorMethod */
        $visitor
            ->expects($this->once())
            ->method($visitorMethod);

        $node->accept($visitor);
    }

    #[DataProviderExternal(NodeAndSerializationProvider::class, 'provide')]
    public function testToString(Node $node, string $serialized): void
    {
        $this->assertEquals($serialized, (string)$node);
    }
}
