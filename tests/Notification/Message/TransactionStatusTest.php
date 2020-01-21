<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Notification\Message;

use Cakasim\Payone\Sdk\Notification\Message\AbstractMessage;
use Cakasim\Payone\Sdk\Notification\Message\TransactionStatus;
use Cakasim\Payone\Sdk\Notification\Message\TransactionStatusInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class TransactionStatusTest extends TestCase
{
    /**
     * @testdox Implemented contracts
     */
    public function testContracts(): void
    {
        $txStatus = new TransactionStatus([]);
        $this->assertInstanceOf(AbstractMessage::class, $txStatus);
        $this->assertInstanceOf(TransactionStatusInterface::class, $txStatus);
    }

    /**
     * @testdox Fixed parameter getters
     */
    public function testGetters(): void
    {
        $txStatus = new TransactionStatus([
            'mode' => 'test',
            'portalid' => '123456789',
            'aid' => '888777',
            'txaction' => 'refund',
            'txtime' => '1579626902',
            'clearingtype' => 'cc',
            'currency' => 'USD',
        ]);

        $this->assertEquals('test', $txStatus->getMode());
        $this->assertEquals('123456789', $txStatus->getPortalId());
        $this->assertEquals('888777', $txStatus->getSubAccountId());
        $this->assertEquals('refund', $txStatus->getAction());
        $this->assertEquals(1579626902, $txStatus->getTime());
        $this->assertEquals('cc', $txStatus->getClearingType());
        $this->assertEquals('USD', $txStatus->getCurrency());
    }

    /**
     * @testdox Custom parameter getter
     */
    public function testCustomParameter(): void
    {
        $txStatus = new TransactionStatus([
            'mode' => 'test',
            'portalid' => '123456789',
            'aid' => '888777',
            'txaction' => 'refund',
            'txtime' => '1579626902',
            'clearingtype' => 'cc',
            'currency' => 'USD',

            'txid' => '1232123432',
            'reference' => 'TH65FT7ZTS',
        ]);

        $this->assertEquals('test', $txStatus->getParameter('mode'));
        $this->assertEquals('1579626902', $txStatus->getParameter('txtime'));
        $this->assertEquals('1232123432', $txStatus->getParameter('txid'));
        $this->assertEquals('TH65FT7ZTS', $txStatus->getParameter('reference'));
        $this->assertNull($txStatus->getParameter('iban'));
        $this->assertNull($txStatus->getParameter('bic'));
    }
}
