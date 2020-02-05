<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Handler;

use Cakasim\Payone\Sdk\Notification\Context\ContextInterface;

/**
 * Manages notification message handlers.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface HandlerManagerInterface
{
    /**
     * Registers the the notification message handler.
     *
     * @param HandlerInterface $handler The handler to register.
     */
    public function registerHandler(HandlerInterface $handler): void;

    /**
     * Forwards the notification to registered handlers.
     *
     * @param ContextInterface $context The notification context which will
     *     be forwarded to each handler.
     */
    public function forwardMessage(ContextInterface $context): void;
}
