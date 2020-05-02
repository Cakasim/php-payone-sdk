<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests;

use Cakasim\Payone\Sdk\Api\Service as ApiService;
use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;
use Cakasim\Payone\Sdk\Http\Service as HttpService;
use Cakasim\Payone\Sdk\Notification\Service as NotificationService;
use Cakasim\Payone\Sdk\Sdk;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class SdkTest extends TestCase
{
    /**
     * @testdox Get container from SDK
     */
    public function testGetContainer(): void
    {
        // SDK with custom container
        $container = new Container();
        $sdk = new Sdk($container);
        $this->assertSame($container, $sdk->getContainer());

        // SDK with default container
        $sdk = new Sdk();
        $this->assertInstanceOf(Container::class, $sdk->getContainer());
    }

    /**
     * @testdox Get HTTP service from SDK
     */
    public function testGetHttpService(): void
    {
        $sdk = new Sdk();
        $this->assertInstanceOf(HttpService::class, $sdk->getHttpService());

        // Get HTTP service from empty container throws an exception
        $container = new Container();
        $sdk = new Sdk($container);
        $this->expectException(ContainerException::class);
        $sdk->getHttpService();
    }

    /**
     * @testdox Get API service from SDK
     */
    public function testGetApiService(): void
    {
        $sdk = new Sdk();
        $this->assertInstanceOf(ApiService::class, $sdk->getApiService());

        // Get API service from empty container throws an exception
        $container = new Container();
        $sdk = new Sdk($container);
        $this->expectException(ContainerException::class);
        $sdk->getApiService();
    }

    /**
     * @testdox Get notification service from SDK
     */
    public function testGetNotificationService(): void
    {
        $sdk = new Sdk();
        $this->assertInstanceOf(NotificationService::class, $sdk->getNotificationService());

        // Get notification service from empty container throws an exception
        $container = new Container();
        $sdk = new Sdk($container);
        $this->expectException(ContainerException::class);
        $sdk->getNotificationService();
    }
}
