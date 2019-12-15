<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * Base class for API messages.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var array The message parameters.
     */
    protected $parameters = [];

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        return \serialize($this->parameters);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        $this->parameters = \unserialize($serialized);
    }
}
