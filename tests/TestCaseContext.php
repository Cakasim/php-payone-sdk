<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests;

use Cakasim\Payone\Sdk\Config\Config;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Container\ContainerException;
use Cakasim\Payone\Sdk\ContainerBuilder;
use Cakasim\Payone\Sdk\Sdk;
use Psr\Container\ContainerInterface;

/**
 * Helper class for initializing the SDK and accessing the SDK
 * services and their components via convenient getters.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.2.0
 */
class TestCaseContext
{
    /**
     * @var Sdk|null The SDK.
     */
    protected $sdk = null;

    /**
     * Creates a new SDK with the provided config and bindings.
     *
     * @param array|null $config The configuration of the SDK.
     * @param array $bindings The bindings of the SDK container.
     */
    public function init(?array $config, array $bindings): void
    {
        try {
            $builder = new ContainerBuilder();
            $container = $builder->getContainer();

            if ($config !== null) {
                $configuration = new Config();

                foreach ($config as $name => $value) {
                    $configuration->set($name, $value);
                }

                $container->bindInstance(ConfigInterface::class, $configuration);
            }

            $classBindings = $bindings['class'] ?? [];
            $instanceBindings = $bindings['instance'] ?? [];
            $callableBindings = $bindings['callable'] ?? [];

            foreach ($classBindings as $id => $definition) {
                $container->bind($id, $definition['concrete'], $definition['singleton']);
            }

            foreach ($instanceBindings as $id => $instance) {
                $container->bindInstance($id, $instance);
            }

            foreach ($callableBindings as $id => $definition) {
                $container->bindCallable($id, $definition['concrete'], $definition['singleton']);
            }

            $this->sdk = new Sdk($builder->buildContainer());
        } catch (ContainerException $e) {
            throw new \RuntimeException('The container bindings are invalid.', 0, $e);
        }

    }

    /**
     * Returns the SDK.
     *
     * @return Sdk The SDK.
     */
    public function getSdk(): Sdk
    {
        if ($this->sdk) {
            return $this->sdk;
        }

        throw new \RuntimeException('Test context is not initialized.');
    }

    /**
     * Returns the SDK container.
     *
     * @return ContainerInterface The SDK container.
     */
    public function getContainer(): ContainerInterface
    {
        return $this->getSdk()->getContainer();
    }

    /**
     * Returns the SDK config.
     *
     * @return ConfigInterface The SDK config.
     */
    public function getConfig(): ConfigInterface
    {
        return $this->getContainer()->get(ConfigInterface::class);
    }
}
