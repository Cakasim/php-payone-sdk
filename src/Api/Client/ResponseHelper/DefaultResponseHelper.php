<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client\ResponseHelper;

use Cakasim\Payone\Sdk\Api\Client\ClientException;
use Cakasim\Payone\Sdk\Api\Client\ErrorResponseException;
use Cakasim\Payone\Sdk\Api\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * The default response helper handles the response as
 * regular PAYONE API parameters.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class DefaultResponseHelper implements ResponseHelperInterface
{
    /**
     * @var DecoderInterface The API format decoder.
     */
    protected $decoder;

    /**
     * Constructs the default response helper.
     *
     * @param DecoderInterface $decoder The API format decoder.
     */
    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    /**
     * @inheritDoc
     */
    public function isResponsible(ResponseInterface $response): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function modifyHttpRequest(HttpRequestInterface $request): HttpRequestInterface
    {
        return $request;
    }

    /**
     * @inheritDoc
     */
    public function makeResponseData(HttpResponseInterface $response)
    {
        // Ensure text/plain content type which indicates a parameter response.
        if (stristr($response->getHeaderLine('Content-Type'), 'text/plain') === false) {
            throw new ClientException("Cannot read response parameters. The 'Content-Type' response header is not 'text/plain'.");
        }

        // Get whole body contents of the response.
        $bodyContents = $response->getBody()->getContents();

        // Expect a non-empty response body.
        if (empty($bodyContents)) {
            throw new ClientException("Cannot read response parameters. The response body is empty.");
        }

        try {
            // Decode response body to parameter array.
            $responseParameters = $this->decoder->decode($bodyContents);
        } catch (DecoderExceptionInterface $e) {
            throw new ClientException("Cannot read response parameters. Failed decoding the response body.", 0, $e);
        }

        // Check for API error response, handle missing status parameter as error.
        if (($responseParameters['status'] ?? 'ERROR') === 'ERROR') {
            throw new ErrorResponseException(
                (int) ($responseParameters['errorcode'] ?? 0),
                $responseParameters['errormessage'] ?? '',
                $responseParameters['customermessage'] ?? ''
            );
        }

        return $responseParameters;
    }
}
