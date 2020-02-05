<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class JsonResponse implements JsonResponseInterface
{
    /**
     * @var array The JSON response data.
     */
    protected $json = [];

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        $encoded = json_encode($this->json);

        if (!is_string($encoded)) {
            throw new \RuntimeException("Cannot serialize JSON response message, failed encoding of JSON data.");
        }

        return $encoded;
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        $this->json = json_decode($serialized, true);
    }

    /**
     * @inheritDoc
     */
    public function parseResponseData($data): void
    {
        if (!is_string($data)) {
            throw new \RuntimeException(sprintf("Cannot parse response data of type '%s', expected a string.", gettype($data)));
        }

        $json = json_decode($data, true);

        if (!is_array($json)) {
            throw new \RuntimeException(sprintf("Failed decoding of JSON response data: %s", json_last_error_msg()));
        }

        $this->json = $json;
    }
}
