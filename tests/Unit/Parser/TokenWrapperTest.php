<?php

declare(strict_types=1);

namespace Tests\Unit\Parser;

use olml89\ODataParser\Lexer\Token\OperatorToken;
use olml89\ODataParser\Lexer\Token\Token;
use olml89\ODataParser\Lexer\Token\TokenKind;
use olml89\ODataParser\Parser\Exception\UnexpectedTokenException;
use olml89\ODataParser\Parser\TokenWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenWrapper::class)]
#[UsesClass(OperatorToken::class)]
#[UsesClass(TokenKind::class)]
#[UsesClass(UnexpectedTokenException::class)]
final class TokenWrapperTest extends TestCase
{
    private bool $advanced = false;

    private function createTokenWrapper(Token $token): TokenWrapper
    {
        $advanceTokenPosition = fn () => $this->advanced = true;

        return new TokenWrapper($token, $advanceTokenPosition);
    }

    public function testIs(): void
    {
        $token = new OperatorToken(TokenKind::Comma);
        $tokenWrapper = $this->createTokenWrapper($token);

        $this->assertTrue($tokenWrapper->is(TokenKind::Comma));
        $this->assertFalse($tokenWrapper->is(TokenKind::Dot));
    }

    public function testConsume(): void
    {
        $token = new OperatorToken(TokenKind::Comma);
        $tokenWrapper = $this->createTokenWrapper($token);

        $this->assertFalse($tokenWrapper->consume(TokenKind::Dot));
        $this->assertFalse($this->advanced);
        $this->assertTrue($tokenWrapper->consume(TokenKind::Comma));
        $this->assertTrue($this->advanced);
    }

    public function testExpect(): void
    {
        $token = new OperatorToken(TokenKind::Comma);
        $tokenWrapper = $this->createTokenWrapper($token);

        $tokenWrapper->expect(TokenKind::Comma);
        $this->assertTrue($this->advanced);

        $this->expectExceptionObject(
            UnexpectedTokenException::wrongTokenKind(
                $token,
                expectedTokenKind: TokenKind::Dot,
            )
        );

        $tokenWrapper->expect(TokenKind::Dot);
    }
}
