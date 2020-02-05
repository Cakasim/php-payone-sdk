<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * The implementation of the API request interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Request extends AbstractMessage implements RequestInterface
{
    /**
     * Constructs the request with provided parameters.
     *
     * @param array $parameters The request parameters.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function getMerchantId(): ?string
    {
        return $this->getParameter('mid');
    }

    /**
     * @inheritDoc
     */
    public function getPortalId(): ?string
    {
        return $this->getParameter('portalid');
    }

    /**
     * @inheritDoc
     */
    public function getApiVersion(): ?string
    {
        return $this->getParameter('api_version');
    }

    /**
     * @inheritDoc
     */
    public function getMode(): ?string
    {
        return $this->getParameter('mode');
    }

    /**
     * @inheritDoc
     */
    protected function generateState(): array
    {
        $state = parent::generateState();

        // Remove sensible API key information before serializing.
        unset($state['key']);

        return $state;
    }

    /**
     * @inheritDoc
     */
    public function applyParameters(array $parameters): void
    {
        // Merge request parameters with provided parameters.
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function makeParameterArray(): array
    {
        return $this->parameters;
    }
}
