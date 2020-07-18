<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests;

use Cakasim\Payone\Sdk\Api\Service as ApiService;
use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\ContainerBuilder;
use Cakasim\Payone\Sdk\Http\Service as HttpService;
use Cakasim\Payone\Sdk\Notification\Service as NotificationService;
use Cakasim\Payone\Sdk\Redirect\Service as RedirectService;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ContainerBuilderTest extends TestCase
{
    /**
     * @testdox Get container
     */
    public function testGetContainer(): void
    {
        $builder = new ContainerBuilder();
        $this->assertInstanceOf(Container::class, $builder->getContainer());
    }

    /**
     * @testdox The container has the SDK services
     */
    public function testSdkServices(): void
    {
        $builder = new ContainerBuilder();
        $container = $builder->getContainer();

        $this->assertTrue($container->has(HttpService::class));
        $this->assertTrue($container->has(ApiService::class));
        $this->assertTrue($container->has(NotificationService::class));
        $this->assertTrue($container->has(RedirectService::class));
    }
}
