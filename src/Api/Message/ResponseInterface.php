<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * The interface for API response messages.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Parses response data.
     *
     * @param mixed $data The response data to parse.
     */
    public function parseResponseData($data): void;
}
