<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Config;

/**
 * An interface for a simple configuration.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ConfigInterface
{
    /**
     * Returns whether the provided entry name exist.
     *
     * @param string $name The entry name.
     * @return bool True if the entry exists.
     */
    public function has(string $name): bool;

    /**
     * Returns the value of an entry.
     *
     * @param string $name The name of the entry.
     * @return mixed The entry value.
     * @throws ConfigExceptionInterface If the entry does not exist or the entry cannot be retrieved.
     */
    public function get(string $name);

    /**
     * Sets the value of an entry.
     *
     * @param string $name The name of the entry.
     * @param mixed $value The value of the entry.
     */
    public function set(string $name, $value): void;
}
