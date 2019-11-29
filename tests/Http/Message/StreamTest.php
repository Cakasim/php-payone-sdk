<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Http\Message;

use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class StreamTest extends TestCase
{
    /** @var StreamFactoryInterface */
    protected $factory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->factory = new StreamFactory();
    }

    /**
     * Provides test data string contents.
     *
     * @return array
     * @throws \Exception
     */
    public function dataStringContents(): array
    {
        return [
            [''],
            [' '],
            ['Hello World!'],
            ['a'],
            ['A'],
            ['aBcDeF'],
            [' aBcDeF '],
            [' aBcDeF'],
            ['aBcDeF '],
            ['AVeryLongConcatenatedWordThatMakesBasicallyNoSenseButIsAGoodTestingValueForAVeryLongString'],
            [random_bytes(1024)],             // 1 KB
            [random_bytes(1024 * 1024)],      // 1 MB
            [random_bytes(1024 * 1024 * 20)], // 10 MB
        ];
    }

    /**
     * Checks whether Stream is PSR-7 implementation.
     */
    public function testIsPsr7Stream(): void
    {
        $stream = $this->factory->createStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    /**
     * Tests various read methods of Stream.
     *
     * @dataProvider dataStringContents
     * @param string $content
     */
    public function testReadFromStream(string $content): void
    {
        $stream = $this->factory->createStream($content);
        $this->assertEquals(0, $stream->tell());
        $this->assertEquals($content, $stream->read(strlen($content) ?: 1));
        $this->assertEquals(strlen($content), $stream->tell());
        $this->assertEquals('', $stream->getContents());
        $this->assertEquals($content, (string) $content);

        $stream->close();
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $this->assertFalse($stream->isSeekable());
        $this->assertNull($stream->getSize());
        $this->assertEquals('', (string) $stream);
    }

    /**
     * Tests various write methods of Stream.
     *
     * @dataProvider dataStringContents
     * @param string $content
     */
    public function testWriteToStream(string $content): void
    {
        $stream = $this->factory->createStream();
        $this->assertEquals(strlen($content), $stream->write($content));
        $this->assertEquals(strlen($content), $stream->getSize());
        $this->assertEquals($content, (string) $stream);

        $detached = $stream->detach();
        $this->assertIsResource($detached);
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $this->assertFalse($stream->isSeekable());
        $this->assertNull($stream->getSize());
        $this->assertEquals('', (string) $stream);
    }

    /**
     * Test seeking to position.
     */
    public function testSeekToPosition(): void
    {
        $stream = $this->factory->createStream('The quick brown fox jumps over the I do not know ...');
        $stream->seek(35);
        $this->assertEquals('I do not know ...', $stream->getContents());
    }

    public function testTellOnInvalidStreamThrowsException(): void
    {
        $stream = $this->factory->createStream();
        $stream->close();

        $this->expectException(RuntimeException::class);
        $stream->tell();
    }

    public function testSeekOnInvalidStreamThrowsException(): void
    {
        $stream = $this->factory->createStream();
        $stream->close();

        $this->expectException(RuntimeException::class);
        $stream->seek(10);
    }

    public function testWriteOnInvalidStreamThrowsException(): void
    {
        $stream = $this->factory->createStream();
        $stream->close();

        $this->expectException(RuntimeException::class);
        $stream->write('Hello World!');
    }

    public function testReadOnInvalidStreamThrowsException(): void
    {
        $stream = $this->factory->createStream();
        $stream->close();

        $this->expectException(RuntimeException::class);
        $stream->read(1024);
    }

    public function testGetContentsOnInvalidStreamThrowsException(): void
    {
        $stream = $this->factory->createStream();
        $stream->close();

        $this->expectException(RuntimeException::class);
        $stream->getContents();
    }
}
