<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * The implementation of the API response interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Response extends AbstractMessage implements ResponseInterface
{
    /**
     * Returns the status of the response.
     *
     * @return string|null The status or null if not set.
     */
    public function getStatus(): ?string
    {
        return $this->getParameter('status');
    }

    /**
     * @inheritDoc
     */
    public function parseResponseData($data): void
    {
        if (is_array($data)) {
            $this->parameters = $data;
        } else {
            throw new \RuntimeException(sprintf("Cannot parse response data of type '%s'.", gettype($data)));
        }
    }
}
