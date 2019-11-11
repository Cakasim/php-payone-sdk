<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Psr\Http\Client\ClientInterface;

/**
 * An interface for factories that generate PSR-18 clients.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ClientFactoryInterface
{
    /**
     * Creates a PSR-18 client.
     *
     * @return ClientInterface A client instance.
     */
    public function createClient(): ClientInterface;
}
