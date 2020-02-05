<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

/**
 * The token signer creates signatures for token payload data.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface SignerInterface
{
    /**
     * Creates a signature of the provided data.
     *
     * @param string $data The data to create a signature for.
     * @return string The signature of the provided data.
     * @throws SignerExceptionInterface If signing of data fails.
     */
    public function createSignature(string $data): string;
}
