<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

use Psr\Http\Message\StreamInterface;

/**
 * The BinaryResponse class represents a special form of API response. For some API requests,
 * the response does not consist of parameters, but raw binary data. This class provides access
 * to the raw body stream. Please note that serialization of the binary data is not supported.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class BinaryResponse implements BinaryResponseInterface
{
    /**
     * @var StreamInterface|null
     */
    protected $data = null;

    /**
     * Returns the raw data.
     *
     * @return StreamInterface|null The raw data as stream or null if no data is available.
     */
    public function getData(): ?StreamInterface
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function parseResponseData($data): void
    {
        if ($data instanceof StreamInterface) {
            $this->data = $data;
        } else {
            throw new \RuntimeException(sprintf("Cannot parse response data of type '%s'.", gettype($data)));
        }
    }

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        return \serialize(null);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        $this->data = \unserialize($serialized);
    }
}
