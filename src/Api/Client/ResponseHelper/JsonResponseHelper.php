<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client\ResponseHelper;

use Cakasim\Payone\Sdk\Api\Client\ClientException;
use Cakasim\Payone\Sdk\Api\Message\JsonResponseInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class JsonResponseHelper implements ResponseHelperInterface
{
    /**
     * @inheritDoc
     */
    public function isResponsible(ResponseInterface $response): bool
    {
        return $response instanceof JsonResponseInterface;
    }

    /**
     * @inheritDoc
     */
    public function modifyHttpRequest(HttpRequestInterface $request): HttpRequestInterface
    {
        return $request->withHeader('Accept', 'application/json');
    }

    /**
     * @inheritDoc
     */
    public function makeResponseData(HttpResponseInterface $response)
    {
        // Ensure application/json content type which indicates a JSON response.
        if (stristr($response->getHeaderLine('Content-Type'), 'application/json') === false) {
            throw new ClientException("Cannot read JSON response. The 'Content-Type' response header is not 'application/json'.");
        }

        // Get whole body contents of the response.
        $bodyContents = $response->getBody()->getContents();

        // Expect a non-empty response body.
        if (empty($bodyContents)) {
            throw new ClientException("Cannot read JSON response. The response body is empty.");
        }

        return $bodyContents;
    }
}
