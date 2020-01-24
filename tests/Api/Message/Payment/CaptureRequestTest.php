<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Message\Payment;

use Cakasim\Payone\Sdk\Api\Message\Payment\CaptureRequest;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class CaptureRequestTest extends TestCase
{
    /**
     * @testdox Get and set transaction ID (txid) parameter
     */
    public function testGetAndSetTransactionId(): void
    {
        // Test missing txid parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
        ]);

        $this->assertNull($request->getTransactionId());

        // Test existing txid parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
            'txid' => '375153957',
        ]);

        $this->assertEquals('375153957', $request->getTransactionId());
        $this->assertSame($request, $request->setTransactionId('100000555'));
        $this->assertEquals('100000555', $request->getTransactionId());
    }

    /**
     * @testdox Get and set sequence number (sequencenumber) parameter
     */
    public function testGetAndSetSequenceNumber(): void
    {
        // Test missing sequencenumber parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
        ]);

        $this->assertNull($request->getSequenceNumber());

        // Test existing txid parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
            'sequencenumber' => '3',
        ]);

        $this->assertEquals(3, $request->getSequenceNumber());
        $this->assertSame($request, $request->setSequenceNumber(6));
        $this->assertEquals(6, $request->getSequenceNumber());
    }

    /**
     * @testdox Get and set amount parameter
     */
    public function testGetAndSetAmount(): void
    {
        // Test missing amount parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
        ]);

        $this->assertNull($request->getAmount());

        // Test existing amount parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
            'amount' => '150',
        ]);

        $this->assertEquals(150, $request->getAmount());
        $this->assertSame($request, $request->setAmount(600));
        $this->assertEquals(600, $request->getAmount());
    }

    /**
     * @testdox Get and set currency parameter
     */
    public function testGetAndSetCurrency(): void
    {
        // Test missing currency parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
        ]);

        $this->assertNull($request->getCurrency());

        // Test existing currency parameter scenario.
        $request = new CaptureRequest([
            'request' => 'capture',
            'currency' => 'EUR',
        ]);

        $this->assertEquals('EUR', $request->getCurrency());
        $this->assertSame($request, $request->setCurrency('USD'));
        $this->assertEquals('USD', $request->getCurrency());
    }
}
