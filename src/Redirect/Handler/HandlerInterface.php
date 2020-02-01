<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Handler;

use Cakasim\Payone\Sdk\Redirect\Context\ContextInterface;

/**
 * The interface for redirect handlers.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
interface HandlerInterface
{
    /**
     * Handles an incoming redirect.
     *
     * @param ContextInterface $context The context of the redirect.
     */
    public function handleRedirect(ContextInterface $context): void;
}
