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
        return isset($this->parameters['sequencenumber'])
            ? (int) $this->parameters['sequencenumber']
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
        $this->parameters['sequencenumber'] = (string) $sequenceNumber;
        return $this;
    }
}
