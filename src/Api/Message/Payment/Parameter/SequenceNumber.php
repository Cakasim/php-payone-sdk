<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message\Payment\Parameter;

/**
 * Implements getter and setter for the sequence number API request parameter.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait SequenceNumber
{
    /**
     * @inheritDoc
     */
    public function getSequenceNumber(): ?int
    {
        $sequenceNumber = $this->getParameter('sequencenumber');
        return $sequenceNumber !== null
            ? (int) $sequenceNumber
            : null;
    }

    /**
     * Sets the sequence number.
     *
     * @param int $sequenceNumber The sequence number.
     * @return $this
     */
    public function setSequenceNumber(int $sequenceNumber): self
    {
        $this->setParameter('sequencenumber', (string) $sequenceNumber);
        return $this;
    }
}
