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
 * Currently this client implementation lacks some major features
 * like HTTP response header parsing as well as response code parsing.
 * However, the response body will be recognized.
 *
 * You may want to use the CurlClient instead.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class StreamClient extends AbstractClient
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // Read basic request parameters.
        $method = $request->getMethod();
        $uri = (string) $request->getUri();
        $body = (string) $request->getBody();
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
        $headers = join("\r\n", $headers);

        // Populate stream_context_create options.
        $options = [
            'method' => $method,
            'header' => $headers,
        ];

        // Add content to options if the request body contains content.
        if (!empty($body)) {
            $options['content'] = $body;
        }

        // Create stream context and execute the HTTP request.
        $streamContext = stream_context_create(['http' => $options]);
        $responseBody = @file_get_contents($uri, false, $streamContext);

        // Create a response body stream.
        $responseBody = $this->createBody($responseBody);

        return $this->createResponse()->withBody($responseBody);
    }
}
