<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Processor;

/**
 * The interface for redirect processors.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ProcessorInterface
{
    /**
     * Processes a redirect.
     *
     * @param string $token The redirect token.
     * @throws ProcessorExceptionInterface If redirect processing fails.
     */
    public function processRedirect(string $token): void;
}
