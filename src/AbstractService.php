<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

use Psr\Container\ContainerInterface;

/**
 * The base class for various SDK services.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractService
{
    /**
     * @var ContainerInterface The SDK service container.
     */
    protected $container;

    /**
     * Constructs the service with the SDK service container.
     *
     * @param ContainerInterface $container The SDK service container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
