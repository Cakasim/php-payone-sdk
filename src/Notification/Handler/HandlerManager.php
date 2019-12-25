<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Handler;

use Cakasim\Payone\Sdk\Notification\Context\ContextInterface;

/**
 * Implementation of the HandlerManagerInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var HandlerInterface[] The notification handlers.
     */
    protected $handlers = [];

    /**
     * @inheritDoc
     */
    public function registerHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function forwardMessage(ContextInterface $context): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handleNotification($context);
        }
    }
}
