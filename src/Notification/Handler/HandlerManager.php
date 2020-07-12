<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Handler;

use Cakasim\Payone\Sdk\Notification\Context\ContextInterface;
use Psr\Log\LoggerInterface;

/**
 * Implementation of the HandlerManagerInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var LoggerInterface The SDK logger.
     */
    protected $logger;

    /**
     * @var HandlerInterface[] The notification handlers.
     */
    protected $handlers = [];

    /**
     * @param LoggerInterface $logger The SDK logger.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function registerHandler(HandlerInterface $handler): void
    {
        $this->logger->info(sprintf("Register PAYONE notification message handler '%s'.", get_class($handler)));
        $this->handlers[] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function forwardMessage(ContextInterface $context): void
    {
        foreach ($this->handlers as $handler) {
            $this->logger->info(sprintf("Forward PAYONE notification message to handler '%s'.", get_class($handler)));
            $handler->handleNotification($context);
        }
    }
}
