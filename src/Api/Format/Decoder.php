<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Format;

/**
 * Implementation of the API format decoder contract.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Decoder implements DecoderInterface
{
    /**
     * @inheritDoc
     */
    public function decode(string $data): array
    {
        // Replace newlines with & in order to
        // use parse_str() for parsing.
        $data = preg_replace('/\r?\n/i', '&', $data);

        $result = [];
        parse_str($data, $result);

        return $result;
    }
}
