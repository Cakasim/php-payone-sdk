<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The base class for clients of this PSR-18 implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * @var ResponseFactoryInterface Holds the concrete PSR-17 response factory
     * instance that is used to create responses for the sent requests.
     */
    protected $responseFactory;

    /**
     * Constructs the client with a response factory.
     *
     * @param ResponseFactoryInterface $responseFactory The response factory instance to use.
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Creates a new response from the response factory.
     *
     * @return ResponseInterface The created PSR-7 response.
     */
    protected function createResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse();
    }
}
