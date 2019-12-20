<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Handler;

use Cakasim\Payone\Sdk\Notification\Context\ContextInterface;

/**
 * Interface for a notification handler.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface HandlerInterface
{
    /**
     * Handles an incoming notification message.
     *
     * @param ContextInterface $context The context of the notification message.
     */
    public function handleNotification(ContextInterface $context): void;
}
