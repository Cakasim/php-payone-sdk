<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment\Parameter;

/**
 * Implements getter and setter for the currency API request parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait Currency
{
    /**
     * @inheritDoc
     */
    public function getCurrency(): ?string
    {
        return $this->getParameter('currency');
    }

    /**
     * Sets the currency.
     *
     * @param string $currency The transaction ID.
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->setParameter('currency', $currency);
        return $this;
    }
}
