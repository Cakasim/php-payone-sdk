<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The StreamClient uses PHP core stream features in order to
 * make HTTP requests. This provides a client implementation
 * with zero dependencies.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class StreamClient extends AbstractClient
{
    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // Make stream context options.
        $options = $this->makeStreamContextOptions($request);

        // Create HTTP stream context.
        $context = stream_context_create(['http' => $options]);

        // Register temporary error handler to catch fopen warnings.
        $err = [];
        set_error_handler(function (int $code, string $message) use (&$err): bool {
            $err['code'] = $code;
            $err['message'] = $message;
            return true;
        });

        // Open the file and restore the error handler.
        $stream = fopen((string) $request->getUri(), 'r', false, $context);
        restore_error_handler();

        if (!empty($err)) {
            throw new ClientException("Failed to send HTTP request: [{$err['code']}] {$err['message']}");
        }

        if (!is_resource($stream)) {
            throw new ClientException("Failed to create HTTP request stream.");
        }

        // Create the HTTP response.
        $response = $this->createResponse();

        // Parse $http_response_header data.
        $response = $this->parseResponseHeaders($response, $http_response_header);

        // Create a response body stream.
        $responseBody = $this->streamFactory->createStreamFromResource($stream);

        return $response->withBody($responseBody);
    }

    /**
     * Makes the stream context options.
     *
     * @param RequestInterface $request The HTTP request.
     * @return array The stream context options.
     */
    protected function makeStreamContextOptions(RequestInterface $request): array
    {
        $options = [
            'method'        => $request->getMethod(),
            'header'        => $this->makeHeaders($request),
            'ignore_errors' => true, // Fetch the content even on failure status codes.
        ];

        $body = $request->getBody()->getContents();

        // Add content to options if the request body is not empty.
        if (!empty($body)) {
            $options['content'] = $body;
        }

        return $options;
    }

    /**
     * Makes the headers suitable for the stream context options.
     *
     * @param RequestInterface $request The HTTP request.
     * @return string The request headers.
     */
    protected function makeHeaders(RequestInterface $request): string
    {
        $headers = [];

        // Populate headers array.
        foreach ($request->getHeaders() as $headerName => $headerValues) {
            foreach ($headerValues as $headerValue) {
                $headers[] = [$headerName, $headerValue];
            }
        }

        // Transform headers array to valid header lines.
        $headers = array_map(function ($header) {
            return "{$header[0]}: {$header[1]}";
        }, $headers);

        // Join header lines by default HTTP header line feed
        return join("\r\n", $headers);
    }

    /**
     * Parses the response headers.
     *
     * @param ResponseInterface $response The HTTP response.
     * @param array $headers The response headers from $http_response_header.
     * @return ResponseInterface The HTTP response configured with headers.
     */
    protected function parseResponseHeaders(ResponseInterface $response, array $headers): ResponseInterface
    {
        // Parse response header lines.
        foreach ($headers as $line) {
            $line = explode(':', $line, 2);
            if (count($line) === 2) {
                $response = $response->withHeader(trim($line[0]), trim($line[1]));
            }
        }

        return $response;
    }
}
