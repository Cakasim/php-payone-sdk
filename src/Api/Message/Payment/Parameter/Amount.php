<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment\Parameter;

/**
 * Implements getter and setter for the amount API request parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait Amount
{
    /**
     * @inheritDoc
     */
    public function getAmount(): ?int
    {
        $amount = $this->getParameter('amount');
        return $amount !== null
            ? (int) $amount
            : null;
    }

    /**
     * Sets the amount.
     *
     * @param int $amount The amount.
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->setParameter('amount', (string) $amount);
        return $this;
    }
}
