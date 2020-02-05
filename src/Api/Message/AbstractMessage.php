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
     * Returns the value of a parameter.
     *
     * @param string $name The parameter name.
     * @return string|null The parameter value or null if no such parameter exists.
     */
    protected function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * Sets the value of a parameter.
     *
     * @param string $name The parameter name.
     * @param string $value The parameter value.
     */
    protected function setParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }

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
