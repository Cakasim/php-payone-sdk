<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Message\ServerRequest;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Implements the PSR-17 server request factory interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    protected const DEFAULT_PROTOCOL_VERSION = ServerRequest::PROTOCOL_VERSION_1_1;

    /**
     * @var UriFactoryInterface The URI factory used for generating request URIs.
     */
    protected $uriFactory;

    /**
     * @var StreamFactoryInterface The stream factory used for generating request bodies.
     */
    protected $streamFactory;

    /**
     * Constructs the RequestFactory.
     *
     * @param UriFactoryInterface $uriFactory A URI factory.
     * @param StreamFactoryInterface $streamFactory A stream factory.
     */
    public function __construct(UriFactoryInterface $uriFactory, StreamFactoryInterface $streamFactory)
    {
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (!($uri instanceof UriInterface)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        $protocolVersion = $this->detectProtocolVersion($serverParams) ?? static::DEFAULT_PROTOCOL_VERSION;
        $body = $this->streamFactory->createStreamFromFile('php://input', 'r');
        $headers = $this->detectRequestHeaders($serverParams);

        $request = new ServerRequest(
            $method,
            $uri,
            $protocolVersion,
            $body,
            $headers,
            $serverParams,
            $_COOKIE,
            $_GET,
            [], // currently no support for uploaded files
            null,
            []
        );

        $parsedBody = $this->detectParsedBody($request);

        if ($parsedBody !== null) {
            $request = $request->withParsedBody($parsedBody);
        }

        return $request;
    }

    /**
     * Detect protocol version from server params.
     *
     * @param array $serverParams The server params.
     * @return string|null The detected protocol version or null if not detectable.
     */
    protected function detectProtocolVersion(array $serverParams): ?string
    {
        $matches = [];
        preg_match('~^HTTP/(\d+(?:\.\d+)?)$~i', trim($serverParams['SERVER_PROTOCOL'] ?? ''), $matches);
        return $matches[1] ?? null;
    }

    /**
     * Detect request headers from server params.
     * Convert all HTTP prefixed server params to headers.
     *
     * @param array $serverParams The server params.
     * @return array The detected headers.
     */
    protected function detectRequestHeaders(array $serverParams): array
    {
        // Filter server params and preserve all HTTP_* params.
        $serverParams = array_filter($serverParams, function ($name) {
            return substr($name, 0, 5) === 'HTTP_';
        }, ARRAY_FILTER_USE_KEY);

        $headers = [];

        foreach ($serverParams as $name => $value) {
            // Remove HTTP_ prefix.
            $name = substr($name, 5);

            // Make name lowercase.
            $name = strtolower($name);

            // Split name into parts.
            $name = explode('_', $name);

            // Make each name part start with uppercase character.
            $name = array_map('ucfirst', $name);

            // Rejoin name parts by - separator.
            $name = join('-', $name);

            // Store the header.
            $headers[$name] = $value;
        }

        return $headers;
    }

    /**
     * Detect parsed body from $_POST.
     *
     * @param ServerRequest $request The request for which the parsed body should be detected.
     * @return array|null The parsed body or null if no parsed body exists.
     */
    protected function detectParsedBody(ServerRequest $request): ?array
    {
        $contentTypeHeader = $request->getHeaderLine('Content-Type');

        return
            strcasecmp($request->getMethod(), 'POST') === 0 &&
            (
                stristr($contentTypeHeader, 'application/x-www-form-urlencoded') !== false ||
                stristr($contentTypeHeader, 'multipart/form-data') !== false
            )
            ? $_POST
            : null;
    }
}
