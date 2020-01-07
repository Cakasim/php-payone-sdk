<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Message\Stream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Implements the PSR-17 stream factory interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class StreamFactory implements StreamFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        // Open PHP temp stream resource.
        $resource = fopen('php://temp', 'w+');

        if (!is_resource($resource)) {
            throw new RuntimeException('Unable to create temp file stream.');
        }

        // Write provided content to stream and rewind the pointer.
        fwrite($resource, $content);
        rewind($resource);

        // Create Stream with the resource.
        return new Stream($resource);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        // Register temporary error handler to catch fopen warnings.
        $err = [];
        set_error_handler(function (int $code, string $message) use (&$err): bool {
            $err['code'] = $code;
            $err['message'] = $message;
            return true;
        });

        // Open the file and restore the error handler.
        $resource = fopen($filename, $mode);
        restore_error_handler();

        // Throw if an error occurred while opening the file.
        if (!empty($err)) {
            throw new RuntimeException("Unable to open file '{$filename}': [{$err['code']}] {$err['message']}");
        }

        if (!is_resource($resource)) {
            throw new RuntimeException("Unable to create resource from file '{$filename}'.");
        }

        return new Stream($resource);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new RuntimeException("Unable to create stream from existing resource.");
        }

        return new Stream($resource);
    }
}
