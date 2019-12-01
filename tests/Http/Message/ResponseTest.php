<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Http\Message;

use Cakasim\Payone\Sdk\Http\Factory\ResponseFactory;
use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ResponseTest extends TestCase
{
    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->responseFactory = new ResponseFactory(
            $this->streamFactory = new StreamFactory()
        );
    }

    /**
     * Checks whether Response is PSR-7 implementation.
     */
    public function testIsPsr7Response(): void
    {
        $response = $this->responseFactory->createResponse();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * Tests the status code.
     */
    public function testStatusCode(): void
    {
        $response = $this->responseFactory->createResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $response = $response->withStatus(404, 'Not Found');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('', $response->getReasonPhrase());
    }

    /**
     * Tests the reason phrase.
     */
    public function testReasonPhrase(): void
    {
        $response = $this->responseFactory->createResponse();
        $this->assertEquals('', $response->getReasonPhrase());
    }
}
