<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Format;

/**
 * Contract for API format encoders.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface EncoderInterface
{
    /**
     * Encodes the provided data to string representation.
     *
     * @param array $data The data to decode.
     * @return string The string encoded data.
     * @throws EncoderExceptionInterface If encoding fails.
     */
    public function encode(array $data): string;
}
