<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Parameter;

/**
 * Makes the target class capable of having
 * the mode parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait Mode
{
    /**
     * @var string The mode.
     */
    protected $mode;

    /**
     * Returns whether a mode is present or not.
     *
     * @return bool True if a mode is present.
     */
    public function hasMode(): bool
    {
        return isset($this->mode);
    }

    /**
     * Returns the mode.
     *
     * @return string|null The mode or null if not present.
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * Sets the mode.
     *
     * @param string|null $mode The mode.
     * @return $this
     */
    public function setMode(?string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }
}
