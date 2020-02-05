<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * The interface for API request messages.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Returns the merchant ID.
     *
     * @return string|null The merchant ID or null if not set.
     */
    public function getMerchantId(): ?string;

    /**
     * Returns the portal ID.
     *
     * @return string|null The portal ID or null if not set.
     */
    public function getPortalId(): ?string;

    /**
     * Returns the API version.
     *
     * @return string|null The API version or null if not set.
     */
    public function getApiVersion(): ?string;

    /**
     * Returns the API mode.
     *
     * @return string|null The API mode or null if not set.
     */
    public function getMode(): ?string;

    /**
     * Applies the provided parameters for this request.
     *
     * @param array $parameters The request parameters.
     */
    public function applyParameters(array $parameters): void;

    /**
     * Makes the parameter array from the request message.
     *
     * @return array The parameter array.
     */
    public function makeParameterArray(): array;
}
