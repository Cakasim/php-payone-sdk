<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The HTTP response message implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Response extends AbstractMessage implements ResponseInterface
{
    /**
     * @var int The response code.
     */
    protected $statusCode;

    /**
     * Constructs an HTTP response.
     *
     * @param int $code The status code.
     * @param string $protocolVersion The HTTP version.
     * @param StreamInterface $body The response body.
     * @param array $headers The response headers.
     */
    public function __construct(int $code, string $protocolVersion, StreamInterface $body, array $headers)
    {
        parent::__construct($protocolVersion, $body, $headers);
        $this->setStatusCode($code);
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code.
     *
     * @param int $code The status code.
     * @return $this
     */
    protected function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return (clone $this)->setStatusCode($code);
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase()
    {
        return '';
    }
}
