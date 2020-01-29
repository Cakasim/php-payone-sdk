<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client\ResponseHelper;

use Cakasim\Payone\Sdk\Api\Client\ClientExceptionInterface;
use Cakasim\Payone\Sdk\Api\Client\ErrorResponseExceptionInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * A client response helper provides specific functionality
 * for handling a certain type of API response.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ResponseHelperInterface
{
    /**
     * Returns true if the helper is responsible for the provided API response.
     *
     * @param ResponseInterface $response The API response.
     * @return bool True if the helper is responsible.
     */
    public function isResponsible(ResponseInterface $response): bool;

    /**
     * Modifies the HTTP request just before sending it.
     *
     * @param HttpRequestInterface $request The HTTP request.
     * @return HttpRequestInterface The modified HTTP request.
     */
    public function modifyHttpRequest(HttpRequestInterface $request): HttpRequestInterface;

    /**
     * Makes API response data from the HTTP response.
     *
     * @param HttpResponseInterface $response The HTTP response.
     * @return mixed The API response data.
     * @throws ClientExceptionInterface If no response data can be made.
     * @throws ErrorResponseExceptionInterface If the response is a PAYONE API error response.
     */
    public function makeResponseData(HttpResponseInterface $response);
}
