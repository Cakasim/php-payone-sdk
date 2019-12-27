<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Cakasim\Payone\Sdk\Api\Message\RequestInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;

/**
 * The interface for API clients.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ClientInterface
{
    /**
     * Sends the provided request message and populates
     * the provided response message with the API response.
     *
     * @param RequestInterface $request The request message to send.
     * @param ResponseInterface $response The response message to populate with the API response.
     * @throws ClientExceptionInterface If sending fails.
     * @throws ErrorResponseExceptionInterface If the response is a PAYONE API error.
     */
    public function sendRequest(RequestInterface $request, ResponseInterface $response): void;
}
