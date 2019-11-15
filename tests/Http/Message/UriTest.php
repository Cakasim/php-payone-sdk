<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Http\Message;

use Cakasim\Payone\Sdk\Http\Message\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class UriTest extends TestCase
{
    /**
     * Provides a set of invalid URIs.
     *
     * @return array
     */
    public function dataInvalidUris(): array
    {
        return [
            [':'],
            [':80'],
            ['host:0'],
            ['host:65536'],
            ['scheme://host:junk'],
        ];
    }

    /**
     * Provides a set of valid URIs.
     *
     * @return array
     */
    public function dataValidUris(): array
    {
        return [
            ['http://example.org'],
            ['http://example.org:443'],
            ['https://www.example.org'],
            ['https://www.example.org:80'],
            ['www.example.org'],
            ['a.b.c.example.org'],
            ['/john/doe'],
            ['/john/doe?test=1'],
            ['/john/doe#kitten'],
            ['/john/doe?test=2#kitten'],
            ['www.example.org/john/doe?test=2#kitten'],
            ['http://www.example.org/john/doe?test=2#kitten'],
            ['https://www.example.org/john/doe?test=2&test2=cute#kitten'],
            ['https://max@www.example.org/john/doe?test=2&test2=cute#kitten'],
            ['https://max:123456@www.example.org/john/doe?test=2&test2=cute#kitten'],
        ];
    }

    /**
     * Provides a set of valid URIs with default ports.
     *
     * @return array
     */
    public function dataValidUrisWithDefaultPorts(): array
    {
        return [
            ['http://example.org:80'],
            ['https://example.org:443'],
        ];
    }

    /**
     * Tests that the Uri class is a valid Psr7 implementation
     */
    public function testPsr7Implementation(): void
    {
        $this->assertInstanceOf(UriInterface::class, new Uri());
    }

    /**
     * @dataProvider dataInvalidUris
     * @param string $uri
     */
    public function testInvalidUriFails(string $uri): void
    {
        $this->assertFalse(parse_url($uri));
        $this->expectException(\InvalidArgumentException::class);
        new Uri($uri);
    }

    /**
     * Tests construction of valid URIs and that
     * the input URI equals the output URI.
     *
     * @dataProvider dataValidUris
     * @param string $uri
     */
    public function testValidUriWorks(string $uri): void
    {
        $this->assertEquals($uri, (string) (new Uri($uri)));
    }

    /**
     * Tests whether the default scheme ports are recognized.
     *
     * @dataProvider dataValidUrisWithDefaultPorts
     * @param string $uri
     */
    public function testSchemeDefaultPortAware(string $uri): void
    {
        $this->assertNull((new Uri($uri))->getPort());
    }

    /**
     * Tests a very long URI which includes all possible components.
     */
    public function testVeryLongUri(): void
    {
        $uri = new Uri('https://max:12345@www.example.org:8080/some/path?arg=whatever#something');

        $this->assertTrue($uri->hasScheme());
        $this->assertTrue($uri->hasAuthority());
        $this->assertTrue($uri->hasUser());
        $this->assertTrue($uri->hasPass());
        $this->assertTrue($uri->hasHost());
        $this->assertTrue($uri->hasPort());
        $this->assertTrue($uri->hasPath());
        $this->assertTrue($uri->hasQuery());
        $this->assertTrue($uri->hasFragment());

        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('max:12345', $uri->getUserInfo());
        $this->assertEquals('www.example.org', $uri->getHost());
        $this->assertEquals(8080, $uri->getPort());
        $this->assertEquals('max:12345@www.example.org:8080', $uri->getAuthority());
        $this->assertEquals('/some/path', $uri->getPath());
        $this->assertEquals('arg=whatever', $uri->getQuery());
        $this->assertEquals('something', $uri->getFragment());
    }

    /**
     * Tests whether URIs are immutable.
     *
     * @dataProvider dataValidUris
     * @param string $uri
     */
    public function testUriIsImmutable(string $uri): void
    {
        $uri = new Uri($uri);

        $uri2 = $uri->withScheme('ssh');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('ssh', $uri2->getScheme());

        $uri2 = $uri->withUserInfo('sam:98765');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('sam:98765', $uri2->getUserInfo());

        $uri2 = $uri->withHost('example.de');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('example.de', $uri2->getHost());

        $uri2 = $uri->withPort(42);
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals(42, $uri2->getPort());

        $uri2 = $uri->withPath('candy/wonderland');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('candy/wonderland', $uri2->getPath());

        $uri2 = $uri->withQuery('apple=no&banana=yes');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('apple=no&banana=yes', $uri2->getQuery());

        $uri2 = $uri->withFragment('cheers');
        $this->assertNotSame($uri, $uri2);
        $this->assertEquals('cheers', $uri2->getFragment());
    }
}
