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
    public function makeParameterArray(): array
    {
        return $this->parameters;
    }
}
