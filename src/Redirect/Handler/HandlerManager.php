<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Handler;

use Cakasim\Payone\Sdk\Redirect\Context\ContextInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var LoggerInterface The SDK logger.
     */
    protected $logger;

    /**
     * @var HandlerInterface[] The redirect handlers.
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
        $this->logger->info(sprintf("Register PAYONE redirect handler '%s'.", get_class($handler)));
        $this->handlers[] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function forwardRedirect(ContextInterface $context): void
    {
        foreach ($this->handlers as $handler) {
            $this->logger->info(sprintf("Forward PAYONE redirect to handler '%s'.", get_class($handler)));
            $handler->handleRedirect($context);
        }
    }
}
