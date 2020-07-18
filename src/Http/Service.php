<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http;

use Cakasim\Payone\Sdk\AbstractService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The HTTP service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var ServerRequestFactoryInterface The PSR-17 server request factory instance.
     */
    protected $serverRequestFactory;

    /**
     * Constructs the HTTP service.
     *
     * @param ServerRequestFactoryInterface $serverRequestFactory The server request factory.
     * @inheritDoc
     */
    public function __construct(
        ContainerInterface $container,
        ServerRequestFactoryInterface $serverRequestFactory
    ) {
        parent::__construct($container);
        $this->serverRequestFactory = $serverRequestFactory;
    }

    /**
     * Create a server request from the current environment.
     *
     * @return ServerRequestInterface The created server request.
     */
    public function createServerRequest(): ServerRequestInterface
    {
        return $this->serverRequestFactory->createServerRequest(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER
        );
    }
}
