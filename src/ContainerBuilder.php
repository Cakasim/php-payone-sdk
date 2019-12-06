<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;

/**
 * Builds the container and applies SDK default dependencies.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ContainerBuilder
{
    /**
     * The SDK services.
     */
    protected const SERVICES = [
        \Cakasim\Payone\Sdk\Log\Service::class,
        \Cakasim\Payone\Sdk\Http\Service::class,
        \Cakasim\Payone\Sdk\Api\Service::class,
    ];

    /**
     * The default dependencies of the SDK.
     */
    protected const DEFAULT_BINDINGS = [

        // --- PSR Bindings ---

        // PSR-3
        \Psr\Log\LoggerInterface::class => [ \Cakasim\Payone\Sdk\Log\SilentLogger::class, true ],

        // PSR-7
        // Concrete PSR-7 implementation is provided by PSR-17
        // factory bindings below.

        // PSR-11
        // Container binds itself within the container constructor.

        // PSR-17
        \Psr\Http\Message\UriFactoryInterface::class      => [ \Cakasim\Payone\Sdk\Http\Factory\UriFactory::class, true ],
        \Psr\Http\Message\StreamFactoryInterface::class   => [ \Cakasim\Payone\Sdk\Http\Factory\StreamFactory::class, true ],
        \Psr\Http\Message\RequestFactoryInterface::class  => [ \Cakasim\Payone\Sdk\Http\Factory\RequestFactory::class, true ],
        \Psr\Http\Message\ResponseFactoryInterface::class => [ \Cakasim\Payone\Sdk\Http\Factory\ResponseFactory::class, true ],

        // PSR-18
        \Psr\Http\Client\ClientInterface::class => [ \Cakasim\Payone\Sdk\Http\Client\StreamClient::class, true ],

        // --- SDK Bindings ---

        // Config
        \Cakasim\Payone\Sdk\Config\ConfigInterface::class => [ \Cakasim\Payone\Sdk\Config\Config::class, true ],

        // API Format
        \Cakasim\Payone\Sdk\Api\Format\EncoderInterface::class => [ \Cakasim\Payone\Sdk\Api\Format\Encoder::class, true ],
        \Cakasim\Payone\Sdk\Api\Format\DecoderInterface::class => [ \Cakasim\Payone\Sdk\Api\Format\Decoder::class, true ],

        // API Client
        \Cakasim\Payone\Sdk\Api\Client\ClientInterface::class => [ \Cakasim\Payone\Sdk\Api\Client\Client::class, true ],
    ];

    /**
     * @var Container The container.
     */
    protected $container;

    /**
     * Constructs the ContainerBuilder.
     *
     * @throws ContainerException
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->bindServices();
    }

    /**
     * Returns the container.
     *
     * @return Container The container.
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Binds the SDK services.
     *
     * @throws ContainerException
     */
    protected function bindServices(): void
    {
        foreach (static::SERVICES as $service) {
            $this->container->bind($service, null, true);
        }
    }

    /**
     * Binds default SDK dependencies for entries that.
     *
     * @throws ContainerException
     */
    protected function bindDefaults(): void
    {
        foreach (static::DEFAULT_BINDINGS as $id => $binding) {
            if (!$this->container->has($id)) {
                $this->container->bind($id, $binding[0], $binding[1]);
            }
        }
    }

    /**
     * Builds the container.
     *
     * @return Container The container.
     * @throws ContainerException
     */
    public function buildContainer(): Container
    {
        $this->bindDefaults();
        return $this->container;
    }

    /**
     * Builds the default container with all SDK dependencies.
     *
     * @return Container The container.
     * @throws ContainerException
     */
    public static function buildDefaultContainer(): Container
    {
        return (new static())->buildContainer();
    }
}
