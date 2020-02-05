<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Processor;

use Psr\Http\Message\ServerRequestInterface;

/**
 * A processor that takes inbound HTTP requests and processes
 * them as PAYONE notification messages.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ProcessorInterface
{
    /**
     * Processes an inbound HTTP request as PAYONE notification message.
     *
     * @param ServerRequestInterface $request The inbound HTTP request.
     * @throws ProcessorExceptionInterface If processing fails.
     */
    public function processRequest(ServerRequestInterface $request): void;
}
