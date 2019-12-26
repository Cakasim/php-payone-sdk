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
     * Generates a representation of the current state for serializing.
     *
     * @return array A serializable representation of the current state.
     */
    protected function generateState(): array
    {
        return $this->parameters;
    }

    /**
     * Restores the state from the provided data.
     *
     * @param array $data The data to restore the state from.
     */
    protected function restoreState(array $data): void
    {
        $this->parameters = $data;
    }

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        return \serialize($this->generateState());
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized): void
    {
        $this->restoreState(\unserialize($serialized));
    }
}
