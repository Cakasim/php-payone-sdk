<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Handler;

use Cakasim\Payone\Sdk\Redirect\Context\ContextInterface;

/**
 * Manages redirect handlers.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
interface HandlerManagerInterface
{
    /**
     * Registers a redirect handler.
     *
     * @param HandlerInterface $handler The redirect handler.
     */
    public function registerHandler(HandlerInterface $handler): void;

    /**
     * Forwards the redirect to registered handlers.
     *
     * @param ContextInterface $context The context of the redirect.
     */
    public function forwardRedirect(ContextInterface $context): void;
}
