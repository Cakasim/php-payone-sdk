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
     * Makes the parameter array from the request message.
     *
     * @return array The parameter array.
     */
    public function makeParameterArray(): array;
}
