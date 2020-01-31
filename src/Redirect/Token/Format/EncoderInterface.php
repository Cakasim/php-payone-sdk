<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;

/**
 * A token encoder transforms a token object into a string representation
 * that can be decoded back to a token object.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface EncoderInterface
{
    /**
     * Encodes the provided token.
     *
     * @param TokenInterface $token The token to encode.
     * @return string The encoded token.
     * @throws EncoderExceptionInterface If the token encoding fails.
     */
    public function encode(TokenInterface $token): string;
}
