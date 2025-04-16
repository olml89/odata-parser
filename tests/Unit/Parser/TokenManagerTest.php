<?php

declare(strict_types=1);

namespace Tests\Unit\Parser;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\TokenOutOfBoundsException;
use olml89\ODataParser\Parser\TokenManager;
use olml89\ODataParser\Parser\TokenWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenManager::class)]
#[UsesClass(TokenWrapper::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenOutOfBoundsException::class)]
final class TokenManagerTest extends TestCase
{
    public function testCount(): void
    {
        $emptyManager = new TokenManager();
        $manager = new TokenManager(new OperatorToken(TokenKind::Equal));

        $this->assertEquals(0, $emptyManager->count());
        $this->assertEquals(1, $manager->count());
    }

    public function testIsEmpty(): void
    {
        $emptyManager = new TokenManager();
        $manager = new TokenManager(new OperatorToken(TokenKind::Equal));

        $this->assertTrue($emptyManager->isEmpty());
        $this->assertFalse($manager->isEmpty());
    }

    public function testEof(): void
    {
        $emptyManager = new TokenManager();
        $oneMemberManager = new TokenManager(new OperatorToken(TokenKind::Equal));
        $twoMemberManager = new TokenManager(
            new OperatorToken(TokenKind::Equal),
            new OperatorToken(TokenKind::Equal)
        );

        $this->assertTrue($emptyManager->eof());
        $this->assertTrue($oneMemberManager->eof());
        $this->assertFalse($twoMemberManager->eof());

        $twoMemberManager->peek();

        $this->assertTrue($oneMemberManager->eof());
    }

    public function testPeek(): void
    {
        $emptyManager = new TokenManager();
        $manager = new TokenManager($token = new OperatorToken(TokenKind::Equal));

        $this->assertEquals($token, $manager->peek()->token);

        $this->expectExceptionObject(
            new TokenOutOfBoundsException(position: 0, count: 0),
        );

        $emptyManager->peek();
    }
}
