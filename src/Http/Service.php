<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\ContextInterface;
use Cakasim\Payone\Sdk\Http\Factory\ClientFactoryInterface;
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
     * @var ClientFactoryInterface The client factory instance which is capable of generating PSR-18 client instances.
     */
    protected $clientFactory;

    /**
     * Constructs the HTTP service with required factories.
     *
     * @param RequestFactoryInterface $requestFactory The request factory instance.
     * @param ResponseFactoryInterface $responseFactory The response factory instance.
     * @param StreamFactoryInterface $streamFactory The stream factory instance.
     * @param ClientFactoryInterface $clientFactory The client factory instance.
     * @inheritDoc
     */
    public function __construct(
        ContextInterface $context,
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        RequestFactoryInterface $requestFactory,
        ResponseFactoryInterface $responseFactory,
        ClientFactoryInterface $clientFactory
    ) {
        parent::__construct($context);
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->clientFactory = $clientFactory;
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
     * Returns the client factory.
     *
     * @return ClientFactoryInterface The client factory instance.
     */
    public function getClientFactory(): ClientFactoryInterface
    {
        return $this->clientFactory;
    }
}
