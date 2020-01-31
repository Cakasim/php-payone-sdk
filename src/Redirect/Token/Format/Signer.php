<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Signer implements SignerInterface
{
    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * Constructs the token signer.
     *
     * @param ConfigInterface $config The SDK config.
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function createSignature(string $data): string
    {
        try {
            return hash_hmac(
                $this->config->get('redirect.token_signing_algo'),
                $data,
                $this->config->get('redirect.token_signing_key'),
                true
            );
        } catch (ConfigExceptionInterface $e) {
            throw new SignerException("Failed signing of provided data.", 0, $e);
        }
    }
}
