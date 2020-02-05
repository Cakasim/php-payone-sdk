<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Http\Message;

use Cakasim\Payone\Sdk\Http\Factory\RequestFactory;
use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use Cakasim\Payone\Sdk\Http\Factory\UriFactory;
use Cakasim\Payone\Sdk\Http\Message\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class RequestTest extends TestCase
{
    /**
     * @var UriFactory
     */
    protected $uriFactory;

    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->requestFactory = new RequestFactory(
            $this->uriFactory = new UriFactory(),
            $this->streamFactory = new StreamFactory()
        );
    }

    /**
     * Checks whether Request is PSR-7 implementation.
     */
    public function testIsPsr7Request(): void
    {
        $request = $this->requestFactory->createRequest('GET', 'http://example.org');
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * Tests the request method.
     */
    public function testRequestMethod(): void
    {
        $request = $this->requestFactory->createRequest('PUT', 'http://example.org');

        $this->assertEquals('PUT', $request->getMethod());
        $request = $request->withMethod('Get');
        $this->assertEquals('Get', $request->getMethod());
    }

    /**
     * Tests the request URI.
     */
    public function testRequestUri(): void
    {
        $request = $this->requestFactory->createRequest('POST', 'http://example.org');

        $this->assertEquals('http', $request->getUri()->getScheme());
        $this->assertEquals('example.org', $request->getUri()->getHost());
        $this->assertEquals('example.org', $request->getHeader('Host')[0]);
        $request = $request->withUri($this->uriFactory->createUri('https://example.com:9000'));
        $this->assertEquals('https', $request->getUri()->getScheme());
        $this->assertEquals('example.com', $request->getUri()->getHost());
        $this->assertEquals('example.com', $request->getHeader('Host')[0]);
        $this->assertEquals(9000, $request->getUri()->getPort());
        $request = $request->withUri($this->uriFactory->createUri('https://example.de:8000'), true);
        $this->assertEquals('https', $request->getUri()->getScheme());
        $this->assertEquals('example.de', $request->getUri()->getHost());
        $this->assertEquals('example.com', $request->getHeader('Host')[0]);
        $this->assertEquals(8000, $request->getUri()->getPort());
    }

    /**
     * Tests the protocol version.
     */
    public function testRequestProtocolVersion(): void
    {
        $request = $this->requestFactory->createRequest('PATCH', 'https://example.de');

        $this->assertEquals(Request::PROTOCOL_VERSION_1_1, $request->getProtocolVersion());
        $request = $request->withProtocolVersion(Request::PROTOCOL_VERSION_2);
        $this->assertEquals(Request::PROTOCOL_VERSION_2, $request->getProtocolVersion());
    }

    /**
     * Tests the request headers.
     */
    public function testRequestHeaders(): void
    {
        $request = $this->requestFactory->createRequest('POST', 'https://example.de');

        $this->assertEquals(['example.de'], $request->getHeader('Host'));
        $request = $request->withHeader('Content-Type', 'application/json');
        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        $request = $request->withHeader('Content-Type', 'text/plain');
        $this->assertEquals(['text/plain'], $request->getHeader('Content-Type'));
        $this->assertEquals('text/plain', $request->getHeaderLine('Content-Type'));
        $request = $request->withoutHeader('Content-Type');
        $this->assertEquals([], $request->getHeader('Content-Type'));
        $this->assertEquals('', $request->getHeaderLine('Content-Type'));

        $request = $request
            ->withAddedHeader('X-Custom-Header', 'Hello')
            ->withAddedHeader('X-Custom-Header', ['World', '!'])
            ->withHeader('X-Another-Header', ['BaskoTheDog']);

        $this->assertEquals(['Hello', 'World', '!'], $request->getHeader('X-Custom-Header'));
        $this->assertEquals('Hello,World,!', $request->getHeaderLine('X-Custom-Header'));
        $this->assertEquals('BaskoTheDog', $request->getHeaderLine('X-Another-Header'));

        $this->assertEquals([
            'Host' => ['example.de'],
            'X-Custom-Header' => ['Hello', 'World', '!'],
            'X-Another-Header' => ['BaskoTheDog'],
        ], $request->getHeaders());
    }

    /**
     * Tests the request body.
     */
    public function testRequestBody(): void
    {
        $request = $this->requestFactory->createRequest('PUT', 'https://example.de');

        $this->assertInstanceOf(StreamInterface::class, $request->getBody());
        $this->assertEquals('', $request->getBody()->getContents());
        $this->assertEquals('', (string) $request->getBody());

        $newBody = $this->streamFactory->createStream('Hello');

        $request = $request->withBody($newBody);
        $this->assertEquals('Hello', (string) $request->getBody());
    }

    /**
     * Tests the request target.
     */
    public function testRequestTarget(): void
    {
        $request = $this->requestFactory->createRequest('GET', 'https://example.de/test/path?param1=value1#testfragment');
        $this->assertEquals('/test/path?param1=value1', $request->getRequestTarget());

        $request = $this->requestFactory->createRequest('GET', 'https://example.de?param1=value1#testfragment');
        $this->assertEquals('/?param1=value1', $request->getRequestTarget());

        $request = $request->withRequestTarget('relative/request/target');
        $this->assertEquals('relative/request/target', $request->getRequestTarget());
    }
}
