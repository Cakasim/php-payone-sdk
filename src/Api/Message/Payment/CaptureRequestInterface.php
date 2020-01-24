<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment;

use Cakasim\Payone\Sdk\Api\Message\RequestInterface;

/**
 * The interface for payment capturing API requests.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface CaptureRequestInterface extends RequestInterface
{
    /**
     * Returns the ID of the transaction.
     *
     * @return string|null The transaction ID or null if not set.
     */
    public function getTransactionId(): ?string;

    /**
     * Returns the sequence number.
     *
     * @return int|null The sequence number or null if not set.
     */
    public function getSequenceNumber(): ?int;

    /**
     * Returns the capture amount of this request.
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
