<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Cakasim\Payone\Sdk\Api\Client\ResponseHelper\BinaryResponseHelper;
use Cakasim\Payone\Sdk\Api\Client\ResponseHelper\DefaultResponseHelper;
use Cakasim\Payone\Sdk\Api\Client\ResponseHelper\JsonResponseHelper;
use Cakasim\Payone\Sdk\Api\Client\ResponseHelper\ResponseHelperInterface;
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
     * @var ResponseHelperInterface[] The response helpers of this client.
     */
    protected $responseHelpers = [];

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

        $this->responseHelpers = [
            new JsonResponseHelper(),
            new BinaryResponseHelper($this->decoder),
            new DefaultResponseHelper($this->decoder),
        ];
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
            'key'                => $this->makeKeyHash(),
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
     * Returns the hash value of the API key.
     *
     * @return string The API key hash.
     * @throws ConfigExceptionInterface If the required configuration is incomplete.
     */
    protected function makeKeyHash(): string
    {
        return hash(
            $this->config->get('api.key_hash_type'),
            $this->config->get('api.key')
        );
    }

    /**
     * Chooses the proper response helper for the provided API response.
     *
     * @param ResponseInterface $response The API response.
     * @return ResponseHelperInterface The chosen response helper.
     * @throws ClientException If no proper response handler could be chosen.
     */
    protected function chooseResponseHelper(ResponseInterface $response): ResponseHelperInterface
    {
        foreach ($this->responseHelpers as $responseHelper) {
            if ($responseHelper->isResponsible($response)) {
                return $responseHelper;
            }
        }

        throw new ClientException("Cannot choose proper response helper.");
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request, ResponseInterface $response): void
    {
        // Get proper response helper for provided API response.
        $responseHelper = $this->chooseResponseHelper($response);

        // Applies the general request parameters.
        $this->applyGeneralRequestParameters($request);

        // Make the parameter array from the request.
        $requestParameters = $request->makeParameterArray();

        // Create ready-to-send HTTP request from parameter array.
        $httpRequest = $this->createHttpRequest($requestParameters);

        // Apply any response helper HTTP request modifications.
        $httpRequest = $responseHelper->modifyHttpRequest($httpRequest);

        // Send the HTTP request to PAYONE.
        $httpResponse = $this->sendHttpRequest($httpRequest);

        // Make the response data from the HTTP response.
        $responseData = $responseHelper->makeResponseData($httpResponse);

        // Delegate further response data parsing to the provided API response.
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
            $request->applyParameters($this->makeGeneralRequestParameters($request));
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
}
