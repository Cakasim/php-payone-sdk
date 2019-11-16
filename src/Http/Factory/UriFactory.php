<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Message\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Implements the PSR-17 URI factory interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class UriFactory implements UriFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
