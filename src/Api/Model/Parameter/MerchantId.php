<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Parameter;

/**
 * Makes the target class capable of having
 * the merchant ID parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait MerchantId
{
    /**
     * @var string The merchant ID.
     */
    protected $merchantId;

    /**
     * Returns whether a merchant ID is present or not.
     *
     * @return bool True if a merchant ID is present.
     */
    public function hasMerchantId(): bool
    {
        return isset($this->merchantId);
    }

    /**
     * Returns the merchant ID.
     *
     * @return string|null The merchant ID or null if not present.
     */
    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    /**
     * Sets the merchant ID.
     *
     * @param string|null $merchantId The merchant ID.
     * @return $this
     */
    public function setMerchantId(?string $merchantId): self
    {
        $this->merchantId = $merchantId;
        return $this;
    }
}
