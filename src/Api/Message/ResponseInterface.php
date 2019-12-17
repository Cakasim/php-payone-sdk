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
     * Parses a response parameter array.
     *
     * @param array $parameters The parameter array to parse.
     * @throws ErrorResponseExceptionInterface If the parsed parameter array is a PAYONE API error.
     */
    public function parseParameterArray(array $parameters): void;
}
