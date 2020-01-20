<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Cakasim\Payone\Sdk\Api\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Api\Format\EncoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\EncoderInterface;
use Cakasim\Payone\Sdk\Api\Message\Parameter\SubAccountIdAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\RequestInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Sdk;
use Psr\Http\Client\ClientExceptionInterface as HttpClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * The API client implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Client implements ClientInterface
{
    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * @var HttpClientInterface The HTTP client.
     */
    protected $httpClient;

    /**
     * @var HttpRequestFactoryInterface The HTTP request factory.
     */
    protected $httpRequestFactory;

    /**
     * @var EncoderInterface The API format encoder.
     */
    protected $encoder;

    /**
     * @var DecoderInterface The API format decoder.
     */
    protected $decoder;

    /**
     * Constructs the API client with dependencies.
     *
     * @param ConfigInterface $config The SDK config.
     * @param HttpClientInterface $httpClient The HTTP client.
     * @param HttpRequestFactoryInterface $httpRequestFactory The HTTP request factory.
     * @param EncoderInterface $encoder The API format encoder.
     * @param DecoderInterface $decoder The APi format decoder.
     */
    public function __construct(
        ConfigInterface $config,
        HttpClientInterface $httpClient,
        HttpRequestFactoryInterface $httpRequestFactory,
        EncoderInterface $encoder,
        DecoderInterface $decoder
    ) {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    /**
     * Returns the general request parameters that
     * will be applied to each request.
     *
     * @return array The general request parameter array.
     * @throws ConfigExceptionInterface If the required configuration is incomplete.
     */
    protected function makeGeneralRequestParameters(RequestInterface $request): array
    {
        $parameters = [
            'api_version'        => '3.11',
            'encoding'           => 'UTF-8',
            'mid'                => $this->config->get('api.merchant_id'),
            'portalid'           => $this->config->get('api.portal_id'),
            'key'                => $this->config->get('api.key_hash'),
            'mode'               => $this->config->get('api.mode'),
            'solution_name'      => Sdk::API_SOLUTION_NAME,
            'solution_version'   => Sdk::API_SOLUTION_VERSION,
            'integrator_name'    => $this->config->get('api.integrator_name'),
            'integrator_version' => $this->config->get('api.integrator_version'),
        ];

        // Check if the request supports the sub account ID (aid) parameter.
        if ($request instanceof SubAccountIdAwareInterface) {
            $parameters['aid'] = $this->config->get('api.sub_account_id');
        }

        return $parameters;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request, ResponseInterface $response): void
    {
        // Applies the general request parameters.
        $this->applyGeneralRequestParameters($request);

        // Make the parameter array from the request.
        $requestParameters = $request->makeParameterArray();

        // Create ready-to-send HTTP request from parameter array.
        $httpRequest = $this->createHttpRequest($requestParameters);

        // Send the HTTP request to PAYONE.
        $httpResponse = $this->sendHttpRequest($httpRequest);

        $responseData = $this->readParametersFromHttpResponse($httpResponse);

        if ($responseData) {
            // Check if response parameters represent an API error.
            $this->checkForErrorResponse($responseData);
        } else {
            // Use raw response body if no response parameters could be read.
            $responseData = $httpResponse->getBody();
        }

        // Delegate further parsing to provided API response.
        $response->parseResponseData($responseData);
    }

    /**
     * Applies the general request parameters to the API request.
     *
     * @param RequestInterface $request The API request to which the parameters will be applied.
     * @throws ClientException If the general parameters cannot be applied to the API request.
     */
    protected function applyGeneralRequestParameters(RequestInterface $request): void
    {
        try {
            // Apply general parameters to the request.
            $request->applyGeneralParameters($this->makeGeneralRequestParameters($request));
        } catch (ConfigExceptionInterface $e) {
            throw new ClientException("Cannot apply general request parameters.", 0, $e);
        }
    }

    /**
     * Creates the HTTP request.
     *
     * @param array $parameters The API parameters of the request.
     * @return HttpRequestInterface The created HTTP request.
     * @throws ClientException If the HTTP request cannot be created.
     */
    protected function createHttpRequest(array $parameters): HttpRequestInterface
    {
        try {
            // Get PAYONE API endpoint from config.
            $endpoint = $this->config->get('api.endpoint');
        } catch (ConfigExceptionInterface $e) {
            throw new ClientException("Cannot create HTTP request.", 0, $e);
        }

        // Create HTTP request via PSR-17 factory.
        $request = $this->httpRequestFactory->createRequest('POST', $endpoint)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        try {
            // Encode the request parameters to API format.
            $body = $this->encoder->encode($parameters);
        } catch (EncoderExceptionInterface $e) {
            throw new ClientException("Cannot create HTTP request.", 0, $e);
        }

        // Write body contents to the request body and rewind the stream.
        $request->getBody()->write($body);
        $request->getBody()->rewind();

        return $request;
    }

    /**
     * Sends the HTTP request.
     *
     * @param HttpRequestInterface $request The HTTP request to send.
     * @return HttpResponseInterface The resulting HTTP response.
     * @throws ClientException If sending of the HTTP request fails.
     */
    protected function sendHttpRequest(HttpRequestInterface $request): HttpResponseInterface
    {
        try {
            return $this->httpClient->sendRequest($request);
        } catch (HttpClientExceptionInterface $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }
    }

    /**
     * Reads the API response parameters if possible.
     *
     * @param HttpResponseInterface $response The HTTP response to read the parameters from.
     * @return array|null The response parameters or null if no parameters can be read.
     * @throws ClientException If reading the response parameters fails.
     */
    protected function readParametersFromHttpResponse(HttpResponseInterface $response): ?array
    {
        // Ensure text/plain content type which indicates a parameter response.
        if (stristr($response->getHeaderLine('Content-Type'), 'text/plain') === false) {
            return null;
        }

        // Get whole body contents of the response.
        $bodyContents = $response->getBody()->getContents();

        // Expect a non-empty response body.
        if (empty($bodyContents)) {
            throw new ClientException("Cannot read response parameters. The response body is empty.");
        }

        try {
            // Decode response body to parameter array.
            return $this->decoder->decode($bodyContents);
        } catch (DecoderExceptionInterface $e) {
            throw new ClientException("Cannot read response parameters. Failed decoding the response body.", 0, $e);
        }
    }

    /**
     * Checks for PAYONE API error.
     *
     * @param array $parameters The response parameters to check.
     * @throws ErrorResponseException If the response parameters represent a PAYONE API error.
     */
    protected function checkForErrorResponse(array $parameters): void
    {
        if (($parameters['status'] ?? 'ERROR') === 'ERROR') {
            throw new ErrorResponseException(
                (int) ($parameters['errorcode'] ?? 0),
                $parameters['errormessage'] ?? '',
                $parameters['customermessage'] ?? ''
            );
        }
    }
}
