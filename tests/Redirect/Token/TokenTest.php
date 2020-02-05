<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Redirect\Token;

use Cakasim\Payone\Sdk\Redirect\Token\Token;
use Cakasim\Payone\Sdk\Redirect\Token\TokenFactory;
use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class TokenTest extends TestCase
{
    /**
     * Creates a token.
     *
     * @param array $data The token data
     * @return TokenInterface The created token.
     */
    protected function createToken(array $data = []): TokenInterface
    {
        return (new TokenFactory())->createToken($data);
    }

    /**
     * @testdox Token contracts
     */
    public function testTokenContracts(): void
    {
        $token = $this->createToken();
        $this->assertInstanceOf(TokenInterface::class, $token);
        $this->assertInstanceOf(JsonSerializable::class, $token);
        $this->assertInstanceOf(Token::class, $token);
    }

    /**
     * @testdox Token getter
     */
    public function testTokenGetter(): void
    {
        $token = $this->createToken([
            'param1' => 'test',
            'param2' => 123,
            'param3' => true,
            'param4' => null,
        ]);

        $this->assertEquals('test', $token->get('param1'));
        $this->assertEquals(123, $token->get('param2'));
        $this->assertEquals(true, $token->get('param3'));
        $this->assertEquals(null, $token->get('param4'));
        $this->assertNull($token->get('no_param'));
    }

    /**
     * @testdox Token serializing
     */
    public function testTokenJsonSerialize(): void
    {
        $data = [
            'param1' => 'test',
            'param2' => 123,
            'param3' => true,
            'param4' => null,
        ];

        $token = $this->createToken($data);
        $tokenSerialized = json_encode($token);
        $this->assertEquals($data, json_decode($tokenSerialized, true));
    }
}
