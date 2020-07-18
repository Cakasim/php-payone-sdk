<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

use Cakasim\Payone\Sdk\Api\Service as ApiService;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Container\ContainerException;
use Cakasim\Payone\Sdk\Http\Service as HttpService;
use Cakasim\Payone\Sdk\Notification\Service as NotificationService;
use Cakasim\Payone\Sdk\Redirect\Service as RedirectService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * The SDK main class.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Sdk
{
    public const API_SOLUTION_NAME = 'Cakasim/PAYONE-PHP-SDK';
    public const API_SOLUTION_VERSION = '0.2.0';

    /**
     * @var ContainerInterface The SDK service container.
     */
    protected $container;

    /**
     * Constructs the SDK with the provided service container.
     *
     * @param ContainerInterface|null $container The service container or null to use the default container.
     * @throws ContainerException If building the default container fails.
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?: ContainerBuilder::buildDefaultContainer();
    }

    /**
     * Returns the SDK service container.
     *
     * @return ContainerInterface The SDK service container.
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Returns the SDK config.
     *
     * @return ConfigInterface The SDK config.
     * @throws ContainerException If the SDK config cannot be resolved.
     */
    public function getConfig(): ConfigInterface
    {
        return $this->container->get(ConfigInterface::class);
    }

    /**
     * Returns the HTTP service.
     *
     * @return HttpService The HTTP service.
     * @throws ContainerExceptionInterface If the HTTP service cannot be resolved.
     */
    public function getHttpService(): HttpService
    {
        return $this->container->get(HttpService::class);
    }

    /**
     * Returns the API service.
     *
     * @return ApiService The API service.
     * @throws ContainerExceptionInterface If the API service cannot be resolved.
     */
    public function getApiService(): ApiService
    {
        return $this->container->get(ApiService::class);
    }

    /**
     * Returns the notification service.
     *
     * @return NotificationService The notification service.
     * @throws ContainerExceptionInterface If the notification service cannot be resolved.
     */
    public function getNotificationService(): NotificationService
    {
        return $this->container->get(NotificationService::class);
    }

    /**
     * Returns the redirect service.
     *
     * @return RedirectService The redirect service.
     * @throws ContainerExceptionInterface If the redirect service cannot be resolved.
     */
    public function getRedirectService(): RedirectService
    {
        return $this->container->get(RedirectService::class);
    }
}
