<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;

/**
 * A token decoder transforms a token string representation
 * back to the token object.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface DecoderInterface
{
    /**
     * Decodes the provided token string representation.
     *
     * @param string $token The encoded token string.
     * @return TokenInterface The token object.
     * @throws DecoderExceptionInterface If decoding the token string representation fails.
     */
    public function decode(string $token): TokenInterface;
}
