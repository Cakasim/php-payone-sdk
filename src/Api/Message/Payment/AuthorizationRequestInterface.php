<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment;

use Cakasim\Payone\Sdk\Api\Message\Parameter\SubAccountIdAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\RequestInterface;

/**
 * The interface for payment (pre)authorization API requests.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface AuthorizationRequestInterface extends RequestInterface, SubAccountIdAwareInterface
{
    /**
     * Returns the sub account ID.
     *
     * @return string|null The sub account ID or null if not set.
     */
    public function getSubAccountId(): ?string;

    /**
     * Returns the clearing type of the transaction.
     *
     * @return string|null The clearing type or null if not set.
     */
    public function getClearingType(): ?string;

    /**
     * Returns the custom reference of the transaction.
     *
     * @return string|null The custom reference or null if not set.
     */
    public function getReference(): ?string;

    /**
     * Returns the transaction amount.
     *
     * @return int|null The amount or null if not set.
     */
    public function getAmount(): ?int;

    /**
     * Returns the transaction currency.
     *
     * @return string|null The ISO 4217 3-letter-code of the currency.
     */
    public function getCurrency(): ?string;
}
