<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token;

/**
 * The interface for redirect token factories.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
interface TokenFactoryInterface
{
    /**
     * Creates a redirect token.
     *
     * @param array $data The token payload data.
     * @return TokenInterface The created redirect token.
     */
    public function createToken(array $data): TokenInterface;
}
