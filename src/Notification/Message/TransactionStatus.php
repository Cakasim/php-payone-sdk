<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Message;

/**
 * Implementation of the TransactionStatusInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class TransactionStatus implements TransactionStatusInterface
{
    /**
     * @var array The transaction status parameters.
     */
    protected $parameters;

    /**
     * Constructs the transaction status message with parameters.
     *
     * @param array $parameters The parameters of the transaction status.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
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
     * @inheritDoc
     */
    public function getMode(): string
    {
        return $this->parameters['mode'];
    }

    /**
     * @inheritDoc
     */
    public function getPortalId(): string
    {
        return $this->parameters['portalid'];
    }

    /**
     * @inheritDoc
     */
    public function getSubAccountId(): string
    {
        return $this->parameters['aid'];
    }

    /**
     * @inheritDoc
     */
    public function getAction(): string
    {
        return $this->parameters['txaction'];
    }

    /**
     * @inheritDoc
     */
    public function getTime(): int
    {
        return (int) $this->parameters['txtime'];
    }

    /**
     * @inheritDoc
     */
    public function getClearingType(): string
    {
        return $this->parameters['clearingtype'];
    }

    /**
     * @inheritDoc
     */
    public function getCurrency(): string
    {
        return $this->parameters['currency'];
    }
}
