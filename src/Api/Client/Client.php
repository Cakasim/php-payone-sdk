<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Cakasim\Payone\Sdk\Api\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Api\Format\EncoderExceptionInterface;
use Cakasim\Payone\Sdk\Api\Format\EncoderInterface;
use Cakasim\Payone\Sdk\Api\Message\RequestInterface;
use Cakasim\Payone\Sdk\Api\Message\ResponseInterface;
use Cakasim\Payone\Sdk\Config\ConfigException;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Sdk;
use Psr\Http\Client\ClientExceptionInterface as HttpClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactoryInterface;

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
     * @throws ConfigException If the required configuration is incomplete.
     */
    protected function getGeneralRequestParameters(): array
    {
        return [
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
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request, ResponseInterface $response): void
    {
        try {
            // Apply general parameters to the request.
            $request->applyGeneralParameters($this->getGeneralRequestParameters());
        } catch (ConfigException $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }

        // Make the parameter array from the request.
        $requestParameters = $request->makeParameterArray();

        try {
            // Encode the request parameters to API format.
            $requestParameters = $this->encoder->encode($requestParameters);
        } catch (EncoderExceptionInterface $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }

        try {
            // Make a new HTTP request via the request factory.
            // Get API endpoint from config and set the content type header.
            $httpRequest = $this->httpRequestFactory->createRequest('POST', $this->config->get('api.endpoint'))
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        } catch (ConfigException $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }

        // Write the encoded parameters to the request body.
        $httpRequest->getBody()->write($requestParameters);

        try {
            $httpResponse = $this->httpClient->sendRequest($httpRequest);
        } catch (HttpClientExceptionInterface $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }

        // Retrieve the response body.
        $httpResponse = (string) $httpResponse->getBody();

        if (empty($httpResponse)) {
            throw new ClientException("Failed to parse API response. The response body is empty.");
        }

        try {
            // Decode response body to parameter array.
            $responseParameters = $this->decoder->decode($httpResponse);
        } catch (DecoderExceptionInterface $e) {
            throw new ClientException("Failed to send API request.", 0, $e);
        }

        // Populate the response message object with the parameter array.
        $response->parseParameterArray($responseParameters);
    }
}
