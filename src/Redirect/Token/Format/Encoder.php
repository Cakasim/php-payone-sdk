<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Encoder implements EncoderInterface
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
    public function encode(TokenInterface $token): string
    {
        // JSON encode the token.
        $token = json_encode($token);

        if ($token === false) {
            throw new EncoderException("Failed token encoding, the token could not be JSON encoded.");
        }

        try {
            // Load token encryption config.
            $encryptionMethod = $this->config->get('redirect.token_encryption_method');
            $encryptionKey = $this->config->get('redirect.token_encryption_key');
        } catch (ConfigExceptionInterface $e) {
            throw new EncoderException("Failed token encoding, the token encryption config is incomplete.", 0, $e);
        }

        // Get binary SHA-256 hash of cleartext encryption key.
        $encryptionKey = hash('sha256', $encryptionKey, true);

        // Make initialization vector.
        $iv = $this->makeIv($encryptionMethod);

        // Encrypt the JSON encoded token.
        $token = $this->encrypt($token, $encryptionMethod, $encryptionKey, $iv);

        try {
            // Sign the encrypted token value.
            $signature = $this->signer->createSignature($token);
        } catch (SignerExceptionInterface $e) {
            throw new EncoderException("Failed token encoding, the token could not be signed.", 0, $e);
        }

        // Encode iv, token and signature.
        $iv = $this->base62Encode($iv);
        $token = $this->base62Encode($token);
        $signature = $this->base62Encode($signature);

        return "{$iv}.{$token}.{$signature}";
    }

    /**
     * Makes an initialization vector with proper length according
     * to the provided cipher method.
     *
     * @param string $method The cipher method.
     * @return string The initialization vector.
     * @throws EncoderExceptionInterface If making the initialization vector fails.
     */
    protected function makeIv(string $method): string
    {
        $length = openssl_cipher_iv_length($method);

        if (!is_int($length)) {
            throw new EncoderException("Failed token encoding, could not get length for initialization vector.");
        }

        try {
            $iv = $length > 0
                ? random_bytes($length)
                : '';
        } catch (\Exception $e) {
            throw new EncoderException("Failed token encoding, could not generate random initialization vector.");
        }


        if (!is_string($iv)) {
            throw new EncoderException("Failed token encoding, could not make the initialization vector.");
        }

        return $iv;
    }

    /**
     * Encrypts the provided data.
     *
     * @param string $data The data to encrypt.
     * @param string $method The cipher method.
     * @param string $key The encryption key.
     * @param string $iv The initialization vector.
     * @return string The encrypted data.
     * @throws EncoderExceptionInterface If the encryption fails.
     */
    protected function encrypt(string $data, string $method, string $key, string $iv): string
    {
        $data = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

        if (!is_string($data)) {
            throw new EncoderException("Failed token encoding, the token could not be encrypted.");
        }

        return $data;
    }

    /**
     * Encodes the provided data with base62.
     *
     * @param string $data The data to encode.
     * @return string The base62 encoded data
     * @throws EncoderExceptionInterface If encoding fails.
     */
    protected function base62Encode(string $data): string
    {
        $data = gmp_import($data);

        if (!($data instanceof \GMP)) {
            throw new EncoderException("Failed token encoding, the token could not be base62 encoded.");
        }

        return gmp_strval($data, 62);
    }
}
