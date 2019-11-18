<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Parameter;

/**
 * Makes the target class capable of having
 * the key parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait Key
{
    /**
     * @var string The key.
     */
    protected $key;

    /**
     * Returns whether a key is present or not.
     *
     * @return bool True if a key is present.
     */
    public function hasKey(): bool
    {
        return isset($this->key);
    }

    /**
     * Returns the key.
     *
     * @return string|null The key or null if not present.
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string|null $key The key.
     * @return $this
     */
    public function setKey(?string $key): self
    {
        $this->key = $key;
        return $this;
    }
}
