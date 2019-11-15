<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Client\AbstractClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * The ClientFactory generates the PSR-18 clients
 * that are shipped with this SDK.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ClientFactory implements ClientFactoryInterface
{
    /**
     * @var string The type (e.g. class name) of the concrete client
     * which must be a subclass of AbstractClient.
     */
    protected $type;

    /**
     * @var ResponseFactoryInterface The response factory that will be passed to the client.
     */
    protected $responseFactory;

    /**
     * @var StreamFactoryInterface The stream factory that will be passed to the client.
     */
    protected $streamFactory;

    /**
     * Constructs the ClientFactory with the given client type and factories.
     *
     * @param string $type The concrete client type (e.g. class name) that must be a subclass of AbstractClient.
     * @param ResponseFactoryInterface $responseFactory The response factory that will be passed to the client.
     * @param StreamFactoryInterface $streamFactory The stream factory that will be passed to the client.
     */
    public function __construct(string $type, ResponseFactoryInterface $responseFactory, StreamFactoryInterface $streamFactory)
    {
        // Assert that $type is a subclass of AbstractClient.
        if (!is_subclass_of($type, AbstractClient::class)) {
            throw new \InvalidArgumentException(sprintf('"%s" must be a subclass of "%s".', $type, AbstractClient::class));
        }

        $this->type = $type;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createClient(): ClientInterface
    {
        return new $this->type($this->responseFactory, $this->streamFactory);
    }
}
