<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Redirect;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\Api\Message\Parameter\BackUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\Parameter\ErrorUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\Parameter\SuccessUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\RequestInterface;
use Cakasim\Payone\Sdk\Redirect\Handler\HandlerInterface;
use Cakasim\Payone\Sdk\Redirect\Handler\HandlerManagerInterface;
use Cakasim\Payone\Sdk\Redirect\Processor\ProcessorExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\Processor\ProcessorInterface;
use Cakasim\Payone\Sdk\Redirect\UrlGenerator\UrlGeneratorExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\UrlGenerator\UrlGeneratorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * The redirect service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 1.0.0
 */
class Service extends AbstractService
{
    /**
     * @var LoggerInterface The SDK logger.
     */
    protected $logger;

    /**
     * @var UrlGeneratorInterface The redirect URL generator.
     */
    protected $urlGenerator;

    /**
     * @var HandlerManagerInterface The redirect handler manager.
     */
    protected $handlerManager;

    /**
     * @var ProcessorInterface The redirect processor.
     */
    protected $processor;

    /**
     * Constructs the redirect service.
     *
     * @param UrlGeneratorInterface $urlGenerator The redirect URL generator.
     * @param HandlerManagerInterface $handlerManager The redirect handler manager.
     * @param ProcessorInterface $processor The redirect processor.
     * @inheritDoc
     */
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        UrlGeneratorInterface $urlGenerator,
        ProcessorInterface $processor,
        HandlerManagerInterface $handlerManager
    ) {
        parent::__construct($container);
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
        $this->processor = $processor;
        $this->handlerManager = $handlerManager;
    }

    /**
     * Applies redirect URL parameters to the provided API request
     * if the API request is aware of redirect URL parameters.
     *
     * @param RequestInterface $request The API request.
     * @param array $data The payload data for each of the redirect URLs.
     * @throws UrlGeneratorExceptionInterface If redirect URL generation fails.
     */
    public function applyRedirectParameters(RequestInterface $request, array $data = []): void
    {
        $parameters = [];
        $createdAt = time();

        if ($request instanceof SuccessUrlAwareInterface) {
            $parameters['successurl'] = $this->urlGenerator->generate(array_merge($data, [
                'status'     => 'success',
                'created_at' => $createdAt,
            ]));
        }

        if ($request instanceof ErrorUrlAwareInterface) {
            $parameters['errorurl'] = $this->urlGenerator->generate(array_merge($data, [
                'status'     => 'error',
                'created_at' => $createdAt,
            ]));
        }

        if ($request instanceof BackUrlAwareInterface) {
            $parameters['backurl'] = $this->urlGenerator->generate(array_merge($data, [
                'status'     => 'back',
                'created_at' => $createdAt,
            ]));
        }

        $this->logger->debug("Apply redirect parameters for PAYONE request.", $parameters);
        $request->applyParameters($parameters);
    }

    /**
     * Processes an inbound redirect.
     *
     * @param string $token The encoded redirect token.
     * @throws ProcessorExceptionInterface If redirect processing fails.
     */
    public function processRedirect(string $token): void
    {
        $this->processor->processRedirect($token);
    }

    /**
     * Registers a redirect handler.
     *
     * @param HandlerInterface $handler The handler to register.
     */
    public function registerHandler(HandlerInterface $handler): void
    {
        $this->handlerManager->registerHandler($handler);
    }
}
