<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Message;

use Psr\Http\Message\UriInterface;

/**
 * Implements the PSR-7 URI interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Uri implements UriInterface
{
    /**
     * @var string The scheme.
     */
    private $scheme;

    /**
     * @var string The username.
     */
    private $user;

    /**
     * @var string The password.
     */
    private $pass;

    /**
     * @var string The hostname.
     */
    private $host;

    /**
     * @var int|null The port.
     */
    private $port;

    /**
     * @var string The path.
     */
    private $path;

    /**
     * @var string The query.
     */
    private $query;

    /**
     * @var string The fragment.
     */
    private $fragment;

    /**
     * Constructs the URI.
     *
     * @param string|null $uri The URI string to parse or null to create an empty URI.
     */
    public function __construct(string $uri = null)
    {
        // Reset attributes.
        $this->reset();

        // Parse URI string if not provided as null value.
        if ($uri !== null) {
            $this->parseUri($uri);
        }
    }

    /**
     * Resets the current state.
     */
    protected function reset(): void
    {
        // Reset attributes.
        $this->scheme = '';
        $this->user = '';
        $this->pass = '';
        $this->host = '';
        $this->port = null;
        $this->path = '';
        $this->query = '';
        $this->fragment = '';
    }

    /**
     * Parses a URI string.
     *
     * @param string $uri The URI string to parse.
     */
    protected function parseUri(string $uri): void
    {
        // Use builtin parse_url() function to parse the URI.
        $parseInfo = parse_url($uri);

        if ($parseInfo === false) {
            throw new \InvalidArgumentException("The URI '{$uri}' could not be parsed by parse_url().");
        }

        // Reset attributes before applying new data.
        $this->reset();

        if (isset($parseInfo['scheme'])) {
            $this->setScheme($parseInfo['scheme']);
        }

        if (isset($parseInfo['user'])) {
            $this->setUser($parseInfo['user']);
        }

        if (isset($parseInfo['pass'])) {
            $this->setPass($parseInfo['pass']);
        }

        if (isset($parseInfo['host'])) {
            $this->setHost($parseInfo['host']);
        }

        if (isset($parseInfo['port'])) {
            $this->setPort($parseInfo['port']);
        }

        if (isset($parseInfo['path'])) {
            $this->setPath($parseInfo['path']);
        }

        if (isset($parseInfo['query'])) {
            $this->setQuery($parseInfo['query']);
        }

        if (isset($parseInfo['fragment'])) {
            $this->setFragment($parseInfo['fragment']);
        }
    }

    /**
     * @inheritDoc
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Returns whether or not a scheme is present.
     *
     * @return bool True if the scheme is present.
     */
    public function hasScheme(): bool
    {
        return !empty($this->scheme);
    }

    /**
     * Sets the scheme of the URI.
     *
     * @param string $scheme The URI scheme.
     * @return $this
     */
    protected function setScheme(string $scheme): self
    {
        // According to PSR-7 the scheme is lowercase.
        $this->scheme = strtolower($scheme);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority()
    {
        return rtrim(ltrim("{$this->getUserInfo()}@{$this->getHost()}:{$this->getPort()}", '@'), ':');
    }

    /**
     * Returns whether or not a authority is present.
     *
     * @return bool True if the authority is present.
     */
    public function hasAuthority(): bool
    {
        return !empty($this->getAuthority());
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo()
    {
        return trim("{$this->user}:{$this->pass}", ':');
    }

    /**
     * Returns whether or not a user is present.
     *
     * @return bool True if the user is present.
     */
    public function hasUser(): bool
    {
        return !empty($this->user);
    }

    /**
     * Sets the username of the URI authority part.
     *
     * @param string $user The name of the user.
     * @return $this
     */
    protected function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Returns whether or not a password is present.
     *
     * @return bool True if the password is present.
     */
    public function hasPass(): bool
    {
        return !empty($this->pass);
    }

    /**
     * Sets the password of the URI authority part.
     *
     * @param string $pass The password of the user.
     * @return $this
     */
    protected function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns whether or not a host is present.
     *
     * @return bool True if the host is present.
     */
    public function hasHost(): bool
    {
        return !empty($this->host);
    }

    /**
     * Sets the hostname of the authority part.
     *
     * @param string $host The hostname.
     * @return $this
     */
    protected function setHost(string $host): self
    {
        // According to PSR-7 the host is lowercase.
        $this->host = strtolower($host);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPort()
    {
        return
            ($this->getScheme() === 'http' && $this->port === 80) ||
            ($this->getScheme() === 'https' && $this->port === 443)
                ? null
                : $this->port;
    }

    /**
     * Returns whether or not a port is present.
     *
     * @return bool True if the port is present.
     */
    public function hasPort(): bool
    {
        return !empty($this->port);
    }

    /**
     * Sets the port of the authority part.
     *
     * @param int|null $port The port number.
     * @return $this
     */
    protected function setPort(?int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns whether or not a path is present.
     *
     * @return bool True if the path is present.
     */
    public function hasPath(): bool
    {
        return !empty($this->path);
    }

    /**
     * Sets the path of the URI.
     *
     * @param string $path The URI path.
     * @return $this
     */
    protected function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Returns whether or not a query is present.
     *
     * @return bool True if the query is present.
     */
    public function hasQuery(): bool
    {
        return !empty($this->query);
    }

    /**
     * Sets the query part of the URI.
     *
     * @param string $query The URI query.
     * @return $this
     */
    protected function setQuery(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Returns whether or not a fragment is present.
     *
     * @return bool True if the fragment is present.
     */
    public function hasFragment(): bool
    {
        return !empty($this->fragment);
    }

    /**
     * Sets the fragment of the URI.
     *
     * @param string $fragment The URI fragment.
     * @return $this
     */
    protected function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme)
    {
        return (clone $this)->setScheme($scheme);
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null)
    {
        return (clone $this)
            ->setUser($user)
            ->setPass($password ?? '');
    }

    /**
     * @inheritDoc
     */
    public function withHost($host)
    {
        return (clone $this)->setHost($host);
    }

    /**
     * @inheritDoc
     */
    public function withPort($port)
    {
        return (clone $this)->setPort($port);
    }

    /**
     * @inheritDoc
     */
    public function withPath($path)
    {
        return (clone $this)->setPath($path);
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query)
    {
        return (clone $this)->setQuery($query);
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        return (clone $this)->setFragment($fragment);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $uri = '';

        if ($this->hasScheme()) {
            $uri .= "{$this->getScheme()}:";
        }

        if ($this->hasAuthority()) {
            $uri .= "//{$this->getAuthority()}";
        }

        if ($this->hasPath()) {
            if ($this->getPath()[0] === '/' || $this->hasAuthority()) {
                $uri .= '/';
            }

            $uri .= ltrim($this->getPath(), '/');
        }

        if ($this->hasQuery()) {
            $uri .= "?{$this->getQuery()}";
        }

        if ($this->hasFragment()) {
            $uri .= "#{$this->getFragment()}";
        }

        return $uri;
    }
}
