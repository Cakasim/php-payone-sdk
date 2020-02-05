<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment;

use Cakasim\Payone\Sdk\Api\Message\Payment\Parameter\Amount;
use Cakasim\Payone\Sdk\Api\Message\Payment\Parameter\Currency;
use Cakasim\Payone\Sdk\Api\Message\Request;

/**
 * Represents a payment (pre)authorization request.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class AuthorizationRequest extends Request implements AuthorizationRequestInterface
{
    use Amount,
        Currency;

    /**
     * @inheritDoc
     */
    public function getSubAccountId(): ?string
    {
        return $this->getParameter('aid');
    }

    /**
     * @inheritDoc
     */
    public function getClearingType(): ?string
    {
        return $this->getParameter('clearingtype');
    }

    /**
     * @inheritDoc
     */
    public function getReference(): ?string
    {
        return $this->getParameter('reference');
    }

    /**
     * Sets the custom reference of this transaction.
     *
     * @param string $reference The reference for this transaction.
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->setParameter('reference', $reference);
        return $this;
    }
}
