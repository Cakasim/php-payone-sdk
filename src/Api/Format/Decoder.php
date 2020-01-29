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
        // Split data by line breaks.
        $data = preg_split('/\r?\n/i', $data);

        if (!is_array($data)) {
            throw new DecoderException("Failed decoding of data.");
        }

        // Split each line by param-value-separator and encode the value component.
        $lines = [];
        foreach ($data as $line) {
            $line = trim($line);
            if (strpos($line, '=') > 0) {
                $line = explode('=', $line, 2);
                $lines[] = "{$line[0]}=" . rawurlencode($line[1]);
            } elseif (!empty($line)) {
                throw new DecoderException("Failed decoding of data.");
            }
        }

        // Join the lines in order to use parse_str().
        $data = join('&', $lines);

        $result = [];
        parse_str($data, $result);

        return $result;
    }
}
