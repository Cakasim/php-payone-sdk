<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Message\Request;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Implements the PSR-17 request factory interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class RequestFactory implements RequestFactoryInterface
{
    /**
     * @var UriFactoryInterface The URI factory used for generating request URIs.
     */
    protected $uriFactory;

    /**
     * @var StreamFactoryInterface The stream factory used for generating request bodies.
     */
    protected $streamFactory;

    /**
     * Constructs the RequestFactory.
     *
     * @param UriFactoryInterface $uriFactory A URI factory.
     * @param StreamFactoryInterface $streamFactory A stream factory.
     */
    public function __construct(UriFactoryInterface $uriFactory, StreamFactoryInterface $streamFactory)
    {
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (!($uri instanceof UriInterface)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        return new Request(
            $method,
            $uri,
            Request::PROTOCOL_VERSION_1_1,
            $this->streamFactory->createStream(),
            []
        );
    }
}
