<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The base class for clients of this PSR-18 implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * @var ResponseFactoryInterface Holds the concrete PSR-17 response factory
     * instance that is used to create responses for the sent requests.
     */
    protected $responseFactory;

    /**
     * @var StreamFactoryInterface Holds the concrete PSR-17 stream factory
     * instance that is used to create the body (stream) for created responses.
     */
    protected $streamFactory;

    /**
     * Constructs the client with a response factory.
     *
     * @param ResponseFactoryInterface $responseFactory The response factory instance to use.
     * @param StreamFactoryInterface $streamFactory The stream factory instance to use.
     */
    public function __construct(ResponseFactoryInterface $responseFactory, StreamFactoryInterface $streamFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Creates a new response from the response factory.
     *
     * @return ResponseInterface The created PSR-7 response.
     */
    protected function createResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse();
    }

    /**
     * Creates a new body stream from provided string content.
     *
     * @param string $content The body content.
     * @return StreamInterface The created stream from the body content.
     */
    protected function createBody(string $content = ''): StreamInterface
    {
        return $this->streamFactory->createStream($content);
    }
}
