<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\UrlGenerator;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\EncoderExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\EncoderInterface;
use Cakasim\Payone\Sdk\Redirect\Token\TokenFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * The name of the token query parameter which will be used
     * if the token value placeholder is not in use.
     */
    protected const TOKEN_PARAMETER_NAME = 't';

    /**
     * The pattern to search in the redirect URL which will
     * be replaced by the token value.
     */
    protected const TOKEN_PLACEHOLDER_PATTERN = '/\$TOKEN/i';

    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * @var TokenFactoryInterface The redirect token factory.
     */
    protected $tokenFactory;

    /**
     * @var EncoderInterface The token encoder.
     */
    protected $encoder;

    /**
     * @var UriFactoryInterface The PSR-7 URI factory.
     */
    protected $uriFactory;

    /**
     * Constructs the URL generator.
     *
     * @param ConfigInterface $config The SDK config.
     * @param TokenFactoryInterface $tokenFactory The redirect token factory.
     * @param EncoderInterface $encoder The token encoder.
     * @param UriFactoryInterface $uriFactory The PSR-7 URI factory.
     */
    public function __construct(
        ConfigInterface $config,
        TokenFactoryInterface $tokenFactory,
        EncoderInterface $encoder,
        UriFactoryInterface $uriFactory
    ) {
        $this->config = $config;
        $this->tokenFactory = $tokenFactory;
        $this->encoder = $encoder;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $data): string
    {
        try {
            $url = $this->config->get('redirect.url');
        } catch (ConfigExceptionInterface $e) {
            throw new UrlGeneratorException("Failed to generate the redirect URL.", 0, $e);
        }

        $token = $this->tokenFactory->createToken($data);

        try {
            $token = $this->encoder->encode($token);
        } catch (EncoderExceptionInterface $e) {
            throw new UrlGeneratorException("Failed to generate the redirect URL, could not encode the token.", 0, $e);
        }

        // Try to replace the token placeholder.
        // Return the URL if the placeholder was replaced.
        if ($this->replaceTokenPlaceholder($url, $token)) {
            return $url;
        }

        // Append the token parameter to the URL.
        return $this->appendDefaultTokenParameter($url, $token);
    }

    /**
     * Replaces the token parameter if a placeholder if used.
     *
     * @param string $url The URL.
     * @param string $token The token value.
     * @return bool True if the token placeholder was replaced.
     */
    protected function replaceTokenPlaceholder(string &$url, string $token): bool
    {
        $count = 0;
        $url = preg_replace(static::TOKEN_PLACEHOLDER_PATTERN, $token, $url, -1, $count);

        return $count > 0;
    }

    /**
     * Appends the default token query parameter (if possible).
     * If the token parameter already exists, it will not be modified.
     *
     * @param string $url The URL to which the token will be appended.
     * @param string $token The token to append.
     * @return string The URL with appended token parameter.
     */
    protected function appendDefaultTokenParameter(string $url, string $token): string
    {
        // Make PSR-7 URI from provided URL and read the query string.
        $uri = $this->uriFactory->createUri($url);
        $query = $uri->getQuery();

        // Parse the query string.
        $queryData = [];
        parse_str($query, $queryData);

        // Return untouched URL if the token parameter already exists.
        if (isset($queryData[static::TOKEN_PARAMETER_NAME])) {
            return $url;
        }

        // Set token parameter.
        $queryData[static::TOKEN_PARAMETER_NAME] = $token;

        // Build the query string.
        $query = http_build_query($queryData);

        return (string) $uri->withQuery($query);
    }
}
