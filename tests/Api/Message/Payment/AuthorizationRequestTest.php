<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Message\Payment;

use Cakasim\Payone\Sdk\Api\Message\Payment\AuthorizationRequest;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class AuthorizationRequestTest extends TestCase
{
    /**
     * @testdox Get sub account ID (aid) parameter
     */
    public function testGetSubAccountId(): void
    {
        // Test missing aid parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
        ]);

        $this->assertNull($request->getSubAccountId());

        // Test existing aid parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
            'aid' => '12345',
        ]);

        $this->assertEquals('12345', $request->getSubAccountId());
    }

    /**
     * @testdox Get clearing type (clearingtype) parameter
     */
    public function testGetClearingType(): void
    {
        // Test missing clearingtype parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
        ]);

        $this->assertNull($request->getClearingType());

        // Test existing clearingtype parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
            'clearingtype' => 'cc',
        ]);

        $this->assertEquals('cc', $request->getClearingType());
    }

    /**
     * @testdox Get and set reference parameter
     */
    public function testGetAndSetReference(): void
    {
        // Test missing reference parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
        ]);

        $this->assertNull($request->getReference());

        // Test existing reference parameter scenario.
        $request = new AuthorizationRequest([
            'request' => 'authorization',
            'reference' => 'abc123',
        ]);

        $this->assertEquals('abc123', $request->getReference());
        $this->assertSame($request, $request->setReference('xyz987'));
        $this->assertEquals('xyz987', $request->getReference());
    }
}
