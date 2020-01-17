<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Cakasim\Payone\Sdk\SdkExceptionInterface;

/**
 * The interface for exceptions thrown because of
 * an error response from the PAYONE API.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ErrorResponseExceptionInterface extends SdkExceptionInterface
{
    /**
     * Returns the PAYONE API error code.
     *
     * @return int The error code.
     * @see https://docs.payone.com/display/public/PLATFORM/Error+messages
     */
    public function getCode();

    /**
     * Returns the internal PAYONE API error message.
     *
     * @return string The error message.
     * @see https://docs.payone.com/display/public/PLATFORM/errormessage+-+definition
     */
    public function getMessage();

    /**
     * Returns the public PAYONE API error message.
     *
     * @return string The customer error message.
     * @see https://docs.payone.com/display/public/PLATFORM/customermessage+-+definition
     */
    public function getCustomerMessage(): string;
}
