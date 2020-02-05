<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

/**
 * The HTTP server request message implementation.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array The server params of this server request.
     */
    protected $serverParams;

    /**
     * @var array The cookie params of this server request.
     */
    protected $cookieParams;

    /**
     * @var array The query params of this server request.
     */
    protected $queryParams;

    /**
     * @var UploadedFileInterface[] The uploaded files of this server request.
     */
    protected $uploadedFiles = [];

    /**
     * @var array|object|null The parsed body data.
     */
    protected $parsedBody = null;

    /**
     * @var array The server request attributes.
     */
    protected $attributes = [];

    /**
     * Constructs the server request.
     *
     * @param string $method The request method.
     * @param UriInterface $uri The request URI.
     * @param string $protocolVersion The HTTP protocol version.
     * @param StreamInterface $body The request body.
     * @param array $headers The request headers.
     * @param array $serverParams The server params.
     * @param array $cookieParams The cookie params.
     * @param array $queryParams  The query params.
     * @param array $uploadedFiles The uploaded files.
     * @param array|object|null $parsedBody The parsed request body.
     * @param array $attributes The request attributes.
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        string $protocolVersion,
        StreamInterface $body,
        array $headers,
        array $serverParams,
        array $cookieParams,
        array $queryParams,
        array $uploadedFiles,
        $parsedBody,
        array $attributes
    ) {
        parent::__construct($method, $uri, $protocolVersion, $body, $headers);
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;
        $this->attributes = $attributes;
    }

    /**
     * @inheritDoc
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * Sets the cookie params.
     *
     * @param array $cookieParams The cookie params.
     * @return $this
     */
    protected function setCookieParams(array $cookieParams): self
    {
        $this->cookieParams = $cookieParams;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        return (clone $this)->setCookieParams($cookies);
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Sets the query params.
     *
     * @param array $queryParams The query params.
     * @return $this
     */
    protected function setQueryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        return (clone $this)->setQueryParams($query);
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * Sets the uploaded files.
     *
     * @param UploadedFileInterface[] $uploadedFiles The uploaded files.
     * @return $this
     */
    protected function setUploadedFiles(array $uploadedFiles): self
    {
        $this->uploadedFiles = $uploadedFiles;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return (clone $this)->setUploadedFiles($uploadedFiles);
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * Sets the parsed body data.
     *
     * @param array|object|null $parsedBody The parsed body data.
     * @return $this
     */
    protected function setParsedBody($parsedBody): self
    {
        $this->parsedBody = $parsedBody;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): self
    {
        return (clone $this)->setParsedBody($data);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Sets an attribute of this server request.
     *
     * @param string $name The attribute name.
     * @param mixed $value The attribute value.
     * @return $this
     */
    protected function setAttribute($name, $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Removes the specified attribute from this server request.
     *
     * @param string $name The attribute name.
     * @return $this
     */
    protected function removeAttribute($name): self
    {
        unset($this->attributes[$name]);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value): self
    {
        return (clone $this)->setAttribute($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name): self
    {
        return (clone $this)->removeAttribute($name);
    }
}
