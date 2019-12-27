<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

use Psr\Http\Message\StreamInterface;

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
     * @param array|StreamInterface $data A parameter array or the raw body stream of the API response.
     */
    public function parseResponseData($data): void;
}
