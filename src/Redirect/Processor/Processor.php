<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect\Processor;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Redirect\Context\Context;
use Cakasim\Payone\Sdk\Redirect\Handler\HandlerManagerInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Redirect\Token\TokenInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Processor implements ProcessorInterface
{
    /**
     * @var LoggerInterface The SDK logger.
     */
    protected $logger;

    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * @var DecoderInterface The redirect token decoder.
     */
    protected $decoder;

    /**
     * @var HandlerManagerInterface The redirect handler manager.
     */
    protected $handlerManager;

    /**
     * Constructs the redirect processor.
     *
     * @param LoggerInterface $logger The SDK logger.
     * @param ConfigInterface $config The SDK config.
     * @param DecoderInterface $decoder The redirect token decoder.
     * @param HandlerManagerInterface $handlerManager The redirect handler manager.
     */
    public function __construct(
        LoggerInterface $logger,
        ConfigInterface $config,
        DecoderInterface $decoder,
        HandlerManagerInterface $handlerManager
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->decoder = $decoder;
        $this->handlerManager = $handlerManager;
    }

    /**
     * @inheritDoc
     */
    public function processRedirect(string $token): void
    {
        try {
            $this->logger->debug(sprintf("Decode PAYONE redirect token '%s'.", $token));
            $token = $this->decoder->decode($token);
        } catch (DecoderExceptionInterface $e) {
            throw new ProcessorException("Failed redirect processing, could not decode the token.", 0, $e);
        }

        $this->verifyToken($token);

        $context = new Context($token);
        $this->handlerManager->forwardRedirect($context);
    }

    /**
     * Verifies the provided token.
     *
     * @param TokenInterface $token The token to verify.
     * @throws ProcessorExceptionInterface If the token verification fails.
     */
    protected function verifyToken(TokenInterface $token): void
    {
        /** @var int|null $createdAt */
        $createdAt = $token->get('created_at');

        if (!is_int($createdAt)) {
            throw new ProcessorException("Failed redirect processing, the provided token has no 'created_at' payload.");
        }

        try {
            /** @var int $lifetime */
            $lifetime = $this->config->get('redirect.token_lifetime');
        } catch (ConfigExceptionInterface $e) {
            throw new ProcessorException("Failed redirect processing, could not get token lifetime from configuration.", 0, $e);
        }

        // Calculate token age.
        $age = time() - $createdAt;

        // Throw exception if token age exceeds configured lifetime.
        if ($age > $lifetime) {
            throw new ProcessorException("Failed redirect processing, the token is expired.");
        }
    }
}
