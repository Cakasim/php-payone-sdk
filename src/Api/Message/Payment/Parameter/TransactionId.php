<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment\Parameter;

/**
 * Implements getter and setter for the transaction ID API request parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait TransactionId
{
    /**
     * @inheritDoc
     */
    public function getTransactionId(): ?string
    {
        return $this->parameters['txid'] ?? null;
    }

    /**
     * Sets the ID of the transaction.
     *
     * @param string $transactionId The transaction ID.
     * @return $this
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->parameters['txid'] = $transactionId;
        return $this;
    }
}
