<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Message;

/**
 * The implementation of the API response interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Response extends AbstractMessage implements ResponseInterface
{
    /**
     * @inheritDoc
     */
    public function parseParameterArray(array $parameters): void
    {
        $this->checkForError($parameters);
        $this->parameters = $parameters;
    }

    /**
     * Throws an ErrorResponseException if the provided
     * parameters indicate a PAYONE API error.
     *
     * @param array $parameters The parameters to check.
     * @throws ErrorResponseException If the parameters indicate a PAYONE API error.
     */
    protected function checkForError(array $parameters): void
    {
        if (($parameters['status'] ?? 'ERROR') === 'ERROR') {
            throw new ErrorResponseException(
                (int) ($parameters['errorcode'] ?? 0),
                $parameters['errormessage'] ?? '',
                $parameters['customermessage'] ?? ''
            );
        }
    }
}
