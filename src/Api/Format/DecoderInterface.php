<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Format;

/**
 * Contract for API format decoders.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface DecoderInterface
{
    /**
     * Decodes the provided data to an array.
     *
     * @param string $data The string data to decode.
     * @return array The data as array.
     * @throws DecoderExceptionInterface If decoding fails.
     */
    public function decode(string $data): array;
}
