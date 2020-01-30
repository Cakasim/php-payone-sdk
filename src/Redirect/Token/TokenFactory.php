<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token;

/**
 * The implementation of the TokenFactoryInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class TokenFactory implements TokenFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createToken(array $data): TokenInterface
    {
        return new Token($data);
    }
}
