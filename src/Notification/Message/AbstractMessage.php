<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Message;

/**
 * The base class for notification messages from PAYONE.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var array The message parameters.
     */
    protected $parameters;

    /**
     * Constructs the message with provided parameters.
     *
     * @param array $parameters The parameters of the message.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

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

    /**
     * Returns the value of the specified parameter.
     *
     * @param string $name The parameter name.
     * @return string|null The parameter value or null if the parameter does not exist.
     */
    public function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }
}
