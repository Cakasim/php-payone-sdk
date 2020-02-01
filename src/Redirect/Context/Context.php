<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Context;

use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class Context implements ContextInterface
{
    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * Constructs the redirect context.
     *
     * @param TokenInterface $token The redirect token.
     */
    public function __construct(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public function getToken(): TokenInterface
    {
        return $this->token;
    }
}
