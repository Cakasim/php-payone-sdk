<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token;

/**
 * The implementation of the TokenInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class Token implements TokenInterface
{
    /**
     * @var array The token data.
     */
    protected $data = [];

    /**
     * Constructs the token.
     *
     * @param array $data The token payload data.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
