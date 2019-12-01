<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http;

use Cakasim\Payone\Sdk\AbstractService;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * The HTTP service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var UriFactoryInterface The PSR-17 URI factory instance.
     */
    protected $uriFactory;

    /**
     * @var StreamFactoryInterface The PSR-17 stream factory instance.
     */
    protected $streamFactory;

    /**
     * @var RequestFactoryInterface The PSR-17 request factory instance.
     */
    protected $requestFactory;

    /**
     * @var ResponseFactoryInterface The PSR-17 response factory instance.
     */
    protected $responseFactory;

    /**
     * @var ClientInterface The HTTP client instance.
     */
    protected $client;

    /**
     * Constructs the HTTP service with required factories.
     *
     * @param RequestFactoryInterface $requestFactory The request factory instance.
     * @param ResponseFactoryInterface $responseFactory The response factory instance.
     * @param StreamFactoryInterface $streamFactory The stream factory instance.
     * @param ClientInterface $clientFactory The HTTP client instance.
     * @inheritDoc
     */
    public function __construct(
        ContainerInterface $container,
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        RequestFactoryInterface $requestFactory,
        ResponseFactoryInterface $responseFactory,
        ClientInterface $client
    ) {
        parent::__construct($container);
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->client = $client;
    }

    /**
     * Returns the PSR-17 URI factory.
     *
     * @return UriFactoryInterface The request factory instance.
     */
    public function getUriFactory(): UriFactoryInterface
    {
        return $this->uriFactory;
    }

    /**
     * Returns the PSR-17 stream factory.
     *
     * @return StreamFactoryInterface The stream factory instance.
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    /**
     * Returns the PSR-17 request factory.
     *
     * @return RequestFactoryInterface The request factory instance.
     */
    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    /**
     * Returns the PSR-17 response factory.
     *
     * @return ResponseFactoryInterface The response factory instance.
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    /**
     * Returns the HTTP client.
     *
     * @return ClientInterface The HTTP client instance.
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
