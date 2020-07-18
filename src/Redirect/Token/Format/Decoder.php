<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Token;
use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Decoder implements DecoderInterface
{
    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * @var SignerInterface The token signer.
     */
    protected $signer;

    /**
     * Constructs the token encoder.
     *
     * @param ConfigInterface $config The SDK config.
     * @param SignerInterface $signer The token signer.
     */
    public function __construct(ConfigInterface $config, SignerInterface $signer)
    {
        $this->config = $config;
        $this->signer = $signer;
    }

    /**
     * @inheritDoc
     */
    public function decode(string $token): TokenInterface
    {
        $token = explode('.', $token, 4);

        if (count($token) !== 3) {
            throw new DecoderException("Failed token decoding, the token encoded format is invalid.");
        }

        // Collect parts of the token.
        $iv = $token[0];
        $signature = $token[2];
        $token = $token[1];

        // Decode the URL safe encoded token parts.
        $iv = $this->urlSafeDecode($iv);
        $signature = $this->urlSafeDecode($signature);
        $token = $this->urlSafeDecode($token);

        try {
            // Create the expected (valid) signature of the token payload data.
            $expectedSignature = $this->signer->createSignature($token);
        } catch (SignerExceptionInterface $e) {
            throw new DecoderException("Failed token decoding, could not create trusted token signature.", 0, $e);
        }

        // Ensure that signatures match.
        if ($signature !== $expectedSignature) {
            throw new DecoderException("Failed token decoding, the token signature is invalid.");
        }

        try {
            // Load token encryption config.
            $encryptionMethod = $this->config->get('redirect.token_encryption_method');
            $encryptionKey = $this->config->get('redirect.token_encryption_key');
        } catch (ConfigExceptionInterface $e) {
            throw new DecoderException("Failed token decoding, the token encryption config is incomplete.", 0, $e);
        }

        // Get binary SHA-256 hash of cleartext encryption key.
        $encryptionKey = hash('sha256', $encryptionKey, true);

        // Decrypt the token payload data.
        $token = $this->decrypt($token, $encryptionMethod, $encryptionKey, $iv);

        // JSON decode the token payload.
        $token = json_decode($token, true);

        if (!is_array($token)) {
            throw new DecoderException("Failed token decoding, the token payload data is invalid.");
        }

        return new Token($token);
    }

    /**
     * Decodes the provided URL-safe encoded data.
     *
     * @param string $data The encoded data to decode.
     * @return string The decoded data.
     * @throws DecoderExceptionInterface If decoding fails.
     */
    protected function urlSafeDecode(string $data): string
    {
        $data = strtr($data, '-_', '+/');
        $data = base64_decode($data, true);

        if ($data === false) {
            throw new DecoderException("Failed token decoding, the token could not be base64 decoding.");
        }

        return $data;
    }

    /**
     * Decrypts the provided data.
     *
     * @param string $data The data to decrypt.
     * @param string $method The cipher method.
     * @param string $key The encryption key.
     * @param string $iv The initialization vector.
     * @return string The decrypted data.
     * @throws DecoderException If the decryption fails.
     */
    protected function decrypt(string $data, string $method, string $key, string $iv): string
    {
        $data = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

        if (!is_string($data)) {
            throw new DecoderException("Failed token decoding, the token could not be decrypted.");
        }

        return $data;
    }
}
