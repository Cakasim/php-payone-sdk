<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\UrlGenerator;

/**
 * The URL generator makes redirect URLs with payload data
 * encoded as redirect token.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
interface UrlGeneratorInterface
{
    /**
     * Generates the redirect URL with appended token which
     * transports the provided payload data.
     *
     * @param array $data The payload data.
     * @return string The generated redirect URL.
     * @throws UrlGeneratorExceptionInterface If URL generation fails.
     */
    public function generate(array $data): string;
}
