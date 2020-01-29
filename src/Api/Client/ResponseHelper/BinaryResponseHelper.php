<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client\ResponseHelper;

use Cakasim\Payone\Sdk\Api\Client\ErrorResponseException;
use Cakasim\Payone\Sdk\Api\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Api\Message\BinaryResponseInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * The binary response helper handles the response as
 * binary data like PDF files.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class BinaryResponseHelper implements ResponseHelperInterface
{
    /**
     * @var DecoderInterface The API format decoder.
     */
    protected $decoder;

    /**
     * Constructs the response helper for binary responses.
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
        return $response instanceof BinaryResponseInterface;
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
        $bodySize = $response->getBody()->getSize();

        // Check for error if an error response (parameter format) is plausible.
        if ($bodySize !== null && $bodySize < 1280) {
            $this->checkForError($response);
        }

        return $response->getBody();
    }

    /**
     * Checks if the response data is a PAYONE API error in parameter format.
     *
     * @param HttpResponseInterface $response The HTTP response.
     * @throws ErrorResponseException If the response data is a PAYONE API error.
     */
    protected function checkForError(HttpResponseInterface $response): void
    {
        // Get whole body contents of the response.
        $bodyContents = $response->getBody()->getContents();

        try {
            // Try to decode the response body to parameter array.
            $responseParameters = $this->decoder->decode($bodyContents);

            if (($responseParameters['status'] ?? null) === 'ERROR') {
                throw new ErrorResponseException(
                    (int) ($responseParameters['errorcode'] ?? 0),
                    $responseParameters['errormessage'] ?? '',
                    $responseParameters['customermessage'] ?? ''
                );
            }
        } catch (DecoderExceptionInterface $e) {
            // Ignore any decoder exceptions because we should assume
            // that the response data is actually just binary data.
        }
    }
}
