<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Format;

/**
 * Implementation of the API format encoder contract.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Encoder implements EncoderInterface
{
    /**
     * @inheritDoc
     */
    public function encode(array $data): string
    {
        return http_build_query($data);
    }
}
