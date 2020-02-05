<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Client;

use Exception;

/**
 * Exception which is thrown because of an
 * error response from the PAYONE API.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ErrorResponseException extends Exception implements ErrorResponseExceptionInterface
{
    /**
     * @var string The customer message.
     */
    protected $customerMessage;

    /**
     * Constructs the exception from
     * PAYONE API error information.
     *
     * @param int $code The PAYONE API error code.
     * @param string $message The internal PAYONE API message.
     * @param string $customerMessage The customer PAYONE API message.
     */
    public function __construct(int $code, string $message, string $customerMessage)
    {
        parent::__construct($message, $code, null);
        $this->customerMessage = $customerMessage;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerMessage(): string
    {
        return $this->customerMessage;
    }
}
