<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Parameter;

/**
 * Makes the target class capable of having
 * the portal ID parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait PortalId
{
    /**
     * @var string The portal ID.
     */
    protected $portalId;

    /**
     * Returns whether a portal ID is present or not.
     *
     * @return bool True if a portal ID is present.
     */
    public function hasPortalId(): bool
    {
        return isset($this->portalId);
    }

    /**
     * Returns the portal ID.
     *
     * @return string|null The portal ID or null if not present.
     */
    public function getPortalId(): ?string
    {
        return $this->portalId;
    }

    /**
     * Sets the portal ID.
     *
     * @param string|null $portalId The portal ID.
     * @return $this
     */
    public function setPortalId(?string $portalId): self
    {
        $this->portalId = $portalId;
        return $this;
    }
}
