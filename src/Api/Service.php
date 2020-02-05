<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\Api\Client\ClientInterface;
use Psr\Container\ContainerInterface;

/**
 * The API service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var ClientInterface The API client.
     */
    protected $client;

    /**
     * Constructs the API service.
     *
     * @inheritDoc
     * @param ClientInterface $client The API client.
     */
    public function __construct(
        ContainerInterface $container,
        ClientInterface $client
    ) {
        parent::__construct($container);
        $this->client = $client;
    }

    /**
     * Returns the API client.
     *
     * @return ClientInterface The API client.
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
