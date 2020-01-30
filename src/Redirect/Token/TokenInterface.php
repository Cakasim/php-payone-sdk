<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token;

use JsonSerializable;

/**
 * Represents a token that is part of a redirect URL.
 * The token may hold additional data that is related
 * to the redirect process.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
interface TokenInterface extends JsonSerializable
{
    /**
     * Returns a token data entry.
     *
     * @param string $name The data entry name.
     * @return mixed|null The data entry value or null if no such entry exists.
     */
    public function get(string $name);
}
