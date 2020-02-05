<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Config;

/**
 * The ConfigInterface implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Config implements ConfigInterface
{
    /**
     * @var array Stores the config entries.
     */
    protected $config = [];

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->config);
    }

    /**
     * @inheritDoc
     */
    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->config[$name];
        }

        throw new ConfigException("Failed to read config entry '{$name}', the entry does not exist.");
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, $value): void
    {
        $this->config[$name] = $value;
    }
}
