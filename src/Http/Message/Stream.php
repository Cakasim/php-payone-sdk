<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Message;

use Exception;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Implements the PSR-7 stream interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Stream implements StreamInterface
{
    /**
     * Contains all readable modes.
     */
    protected const READABLE_MODES = ['r', 'r+', 'w+', 'a+', 'x+', 'c+'];

    /**
     * Contains all writeable modes.
     */
    protected const WRITEABLE_MODES = ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+'];

    /**
     * @var resource|null The underlying stream resource or null if the stream is invalid.
     */
    protected $stream;

    /**
     * Constructs the Stream.
     *
     * @param resource $stream The resource to construct the stream with.
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Checks whether the stream is valid.
     * The stream is valid if the underlying
     * resource is valid.
     *
     * @return bool True if the stream is valid.
     */
    protected function isValid(): bool
    {
        return is_resource($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        if (!$this->isValid()) {
            return null;
        }

        $meta = stream_get_meta_data($this->stream);

        return is_string($key)
            ? ($meta[$key] ?? null)
            : $meta;
    }

    /**
     * Returns stats about the underlying stream.
     *
     * @param string|null $key The key of the concrete stat to return.
     * @return array|mixed|null All stats if key is null, the requested stat or null if the stat does not exist.
     */
    protected function getStats(string $key = null)
    {
        if (!$this->isValid()) {
            return null;
        }

        $stats = fstat($this->stream);

        return is_string($key)
            ? ($stats[$key] ?? null)
            : $stats;
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        // Attempt to close the valid stream.
        if ($this->isValid()) {
            fclose($this->stream);
        }

        // Set stream reference to null.
        // The stream is now invalid.
        $this->stream = null;
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $stream = null;

        if ($this->isValid()) {
            $stream = $this->stream;
            $this->stream = null;
        }

        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return $this->getStats('size');
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        if (!$this->isValid()) {
            throw new RuntimeException('The stream is invalid.');
        }

        $pos = ftell($this->stream);

        if (!is_int($pos)) {
            throw new RuntimeException('Failed to tell file pointer position.');
        }

        return $pos;
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        // Return feof() result for valid streams, otherwise true.
        return $this->isValid() ? feof($this->stream) : true;
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return $this->getMetadata('seekable') === true;
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new \RuntimeException('The stream is not seekable.');
        }

        if (fseek($this->stream, $offset, $whence) !== 0) {
            throw new \RuntimeException('Failed to seek in the stream.');
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        if (!$this->isSeekable() || !rewind($this->stream)) {
            throw new RuntimeException('Failed to rewind stream.');
        }
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        $mode = $this->getMetadata('mode');

        if ($mode === null) {
            return false;
        }

        foreach (static::WRITEABLE_MODES as $m) {
            if (strstr($mode, $m) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        if (!$this->isWritable()) {
            throw new RuntimeException('The stream is not writable.');
        }

        $writtenBytes = fwrite($this->stream, $string);

        if (!is_int($writtenBytes)) {
            throw new RuntimeException('Failed writing to stream.');
        }

        return $writtenBytes;
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        $mode = $this->getMetadata('mode');

        if ($mode === null) {
            return false;
        }

        foreach (static::READABLE_MODES as $m) {
            if (strstr($mode, $m) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('The stream is not readable.');
        }

        $data = fread($this->stream, $length);

        if (!is_string($data)) {
            throw new RuntimeException('Failed to read from stream.');
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        if (!$this->isValid()) {
            throw new RuntimeException('The stream is invalid.');
        }

        $contents = stream_get_contents($this->stream);

        if (!is_string($contents)) {
            throw new RuntimeException('Failed to read remaining stream contents.');
        }

        return $contents;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        try {
            $this->rewind();
            return $this->getContents();
        } catch (Exception $e) {
            // Do not raise any exceptions!
            return '';
        }
    }
}
