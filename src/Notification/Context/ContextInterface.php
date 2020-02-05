<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Context;

use Cakasim\Payone\Sdk\Notification\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The context of an incoming notification message from PAYONE.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ContextInterface
{
    /**
     * Returns the inbound HTTP request.
     *
     * @return ServerRequestInterface The inbound HTTP request.
     */
    public function getRequest(): ServerRequestInterface;

    /**
     * Returns the message.
     *
     * @return MessageInterface The notification message.
     */
    public function getMessage(): MessageInterface;
}
