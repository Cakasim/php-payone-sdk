<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Message;

use Cakasim\Payone\Sdk\Api\Message\BinaryResponse;
use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class BinaryResponseTest extends TestCase
{
    /**
     * @testdox Handles response data as expected
     */
    public function testParseResponseData(): void
    {
        $stream = (new StreamFactory())->createStream();
        $response = new BinaryResponse();
        $response->parseResponseData($stream);
        $this->assertSame($stream, $response->getData());
    }

    /**
     * @testdox Parsing invalid response data throws exception
     */
    public function testParseInvalidResponseData(): void
    {
        $response = new BinaryResponse();
        $this->expectException(RuntimeException::class);
        $response->parseResponseData([]);
    }

    /**
     * @testdox Serializing of binary data is not supported
     */
    public function testSerializing(): void
    {
        $stream = (new StreamFactory())->createStream();
        $response = new BinaryResponse();
        $response->parseResponseData($stream);

        $serialized = serialize($response);
        $response = unserialize($serialized);

        $this->assertInstanceOf(BinaryResponse::class, $response);
        $this->assertNull($response->getData());
    }
}
