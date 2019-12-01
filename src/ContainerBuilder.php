<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

use Cakasim\Payone\Sdk\Api\Format\Decoder;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Api\Format\Encoder;
use Cakasim\Payone\Sdk\Api\Format\EncoderInterface;
use Cakasim\Payone\Sdk\Api\Service as ApiService;
use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;
use Cakasim\Payone\Sdk\Http\Client\StreamClient;
use Cakasim\Payone\Sdk\Http\Factory\RequestFactory;
use Cakasim\Payone\Sdk\Http\Factory\ResponseFactory;
use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use Cakasim\Payone\Sdk\Http\Factory\UriFactory;
use Cakasim\Payone\Sdk\Http\Service as HttpService;
use Cakasim\Payone\Sdk\Log\Service as LogService;
use Cakasim\Payone\Sdk\Log\SilentLogger;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

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
        LogService::class,
        HttpService::class,
        ApiService::class
    ];

    /**
     * The default dependencies of the SDK.
     */
    protected const DEFAULT_BINDINGS = [

        // --- PSR Bindings ---

        // PSR-3
        LoggerInterface::class => [SilentLogger::class, true],

        // PSR-7
        // Concrete PSR-7 implementation is provided by PSR-17
        // factory bindings below.

        // PSR-11
        // Container binds itself within the container constructor.

        // PSR-17
        UriFactoryInterface::class      => [UriFactory::class, true],
        StreamFactoryInterface::class   => [StreamFactory::class, true],
        RequestFactoryInterface::class  => [RequestFactory::class, true],
        ResponseFactoryInterface::class => [ResponseFactory::class, true],

        // PSR-18
        ClientInterface::class => [StreamClient::class, true],

        // --- SDK Bindings ---

        // API Format
        EncoderInterface::class => [Encoder::class, true],
        DecoderInterface::class => [Decoder::class, true],
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
