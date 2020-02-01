<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Handler;

use Cakasim\Payone\Sdk\Redirect\Context\ContextInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var HandlerInterface[] The redirect handlers.
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
    public function forwardRedirect(ContextInterface $context): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handleRedirect($context);
        }
    }
}
