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
        // Transform nested parameters.
        $data = static::transformData($data, null);

        // Make param=value lines array.
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = "{$key}=" . rawurlencode((string) $value);
        }

        // Combine and return the lines.
        return join("\n", $result);
    }

    /**
     * Transform nested data.
     *
     * @param array $data The data to transform.
     * @param string|null $prefix The prefix to prepend to keys.
     * @return array The transformed data.
     */
    protected static function transformData(array $data, ?string $prefix): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            // Prepend prefix to current key.
            $key = $prefix ? "{$prefix}[{$key}]" : $key;

            // If the value is an array, process this value recursively.
            is_array($value)
                ? $result = array_merge($result, static::transformData($value, $key))
                : $result[$key] = $value;
        }

        return $result;
    }
}
