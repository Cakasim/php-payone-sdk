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
     * The static configuration defaults.
     */
    protected const DEFAULTS = [
        'api.endpoint'      => 'https://api.pay1.de/post-gateway/',
        'api.key_hash_type' => 'sha384',
        'api.mode'          => 'test',

        'notification.sender_address_whitelist' => [
            '185.60.20.0/24',
            '217.70.200.0/24',
            '213.178.72.196',
            '213.178.72.197',
        ],

        'redirect.token_lifetime'          => 3600,
        'redirect.token_encryption_method' => 'aes-256-ctr',
        'redirect.token_signing_algo'      => 'sha256',
    ];

    /**
     * @var array Stores the config entries.
     */
    protected $config = [];

    /**
     * Constructs the config with defaults.
     */
    public function __construct()
    {
        $this->applyDefaults();
    }

    /**
     * Applies the configuration defaults.
     */
    protected function applyDefaults(): void
    {
        foreach ($this->getDefaults() as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Returns the configuration defaults.
     *
     * @return array The configuration defaults.
     */
    protected function getDefaults(): array
    {
        return static::DEFAULTS;
    }

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
