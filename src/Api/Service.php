<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\Api\Client\ClientExceptionInterface;
use Cakasim\Payone\Sdk\Api\Client\ClientInterface;
use Cakasim\Payone\Sdk\Api\Client\ErrorResponseExceptionInterface;
use Cakasim\Payone\Sdk\Api\Message\RequestInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

/**
 * The API service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var ClientInterface The API client.
     */
    protected $client;

    /**
     * Constructs the API service.
     *
     * @inheritDoc
     * @param ClientInterface $client The API client.
     */
    public function __construct(
        ContainerInterface $container,
        ClientInterface $client
    ) {
        parent::__construct($container);
        $this->client = $client;
    }

    /**
     * Sends the provided request message and populates
     * the provided response message with the API response.
     *
     * @param RequestInterface $request The request message to send.
     * @param ResponseInterface $response The response message to populate with the API response.
     * @throws ClientExceptionInterface If sending fails.
     * @throws ErrorResponseExceptionInterface If the response is a PAYONE API error.
     */
    public function sendRequest(RequestInterface $request, ResponseInterface $response): void
    {
        $this->client->sendRequest($request, $response);
    }
}
