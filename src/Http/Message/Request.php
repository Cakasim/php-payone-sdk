<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * The HTTP request message implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Request extends AbstractMessage implements RequestInterface
{
    /**
     * @var string The request method.
     */
    protected $method;

    /**
     * @var UriInterface The request URI.
     */
    protected $uri;

    /**
     * @var string|null The request target or null if no concrete target is set.
     */
    protected $requestTarget = null;

    /**
     * Constructs an HTTP request.
     *
     * @param string $method The request method.
     * @param UriInterface $uri The request URI.
     * @param string $protocolVersion The HTTP version.
     * @param StreamInterface $body The body of this request.
     * @param string[] $headers The headers of this request.
     */
    public function __construct(string $method, UriInterface $uri, string $protocolVersion, StreamInterface $body, array $headers)
    {
        parent::__construct($protocolVersion, $body, $headers);
        $this->setMethod($method);
        $this->setUri($uri);

        // According to PSR-7 set the Host header from the
        // provided URI if not already set.
        if (empty($this->getHeader('Host')) && !empty($host = $uri->getHost())) {
            $this->setHeader('Host', [$host]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        $path = "{$this->getUri()->getPath()}?{$this->getUri()->getQuery()}";
        $path = '/' . ltrim($path, '/');
        $path = rtrim($path, '?');

        return $path;
    }

    /**
     * Sets the request target.
     *
     * @param string $requestTarget The request target.
     * @return $this
     */
    protected function setRequestTarget(string $requestTarget): self
    {
        $this->requestTarget = $requestTarget;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        return (clone $this)->setRequestTarget($requestTarget);
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the request method.
     *
     * @param string $method The request method.
     * @return $this
     */
    protected function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        return (clone $this)->setMethod($method);
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri The URI.
     * @return $this
     */
    protected function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $req = clone $this;

        // According to PSR-7 update the Host header if the host part of
        // the provided URI is not empty and if $preserveHost is not true
        // or the current request has no or an empty Host header.
        if (!empty($host = $uri->getHost()) && ($preserveHost !== true || empty($req->getHeader('Host')))) {
            $req->setHeader('Host', [$host]);
        }

        return $req->setUri($uri);
    }
}
