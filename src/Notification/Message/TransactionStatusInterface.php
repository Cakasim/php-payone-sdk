<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Message;

/**
 * Represents an incoming transaction status message from PAYONE.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface TransactionStatusInterface extends MessageInterface
{
    /**
     * Returns the PAYONE API mode (test or live).
     *
     * @return string The API mode.
     */
    public function getMode(): string;

    /**
     * Returns the associated portal ID.
     *
     * @return string The portal ID.
     */
    public function getPortalId(): string;

    /**
     * Returns the associated sub account ID.
     *
     * @return string The sub account ID.
     */
    public function getSubAccountId(): string;

    /**
     * Returns the action of this transaction status message.
     *
     * @return string The status code of the transaction status.
     */
    public function getAction(): string;

    /**
     * Returns the timestamp of the transaction status message.
     *
     * @return int A UNIX timestamp in seconds.
     */
    public function getTime(): int;

    /**
     * Returns the clearing type of the transaction.
     *
     * @return string The clearing type.
     */
    public function getClearingType(): string;

    /**
     * Returns the currency code of the transaction.
     *
     * @return string The currency code as ISO 4217.
     */
    public function getCurrency(): string;
}
