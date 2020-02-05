<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\Notification\Handler\HandlerInterface;
use Cakasim\Payone\Sdk\Notification\Handler\HandlerManagerInterface;
use Cakasim\Payone\Sdk\Notification\Processor\ProcessorExceptionInterface;
use Cakasim\Payone\Sdk\Notification\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The notification service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var ProcessorInterface The notification processor.
     */
    protected $processor;

    /**
     * @var HandlerManagerInterface The notification handler manager.
     */
    protected $handlerManager;

    /**
     * Constructs the notification service.
     *
     * @param ProcessorInterface $processor The notification processor.
     * @param HandlerManagerInterface $handlerManager The notification handler manager.
     * @inheritDoc
     */
    public function __construct(
        ContainerInterface $container,
        ProcessorInterface $processor,
        HandlerManagerInterface $handlerManager
    ) {
        parent::__construct($container);
        $this->processor = $processor;
        $this->handlerManager = $handlerManager;
    }

    /**
     * Processes an inbound HTTP request as PAYONE notification message.
     *
     * @param ServerRequestInterface $request The inbound HTTP request.
     * @throws ProcessorExceptionInterface If processing fails.
     */
    public function processRequest(ServerRequestInterface $request): void
    {
        $this->processor->processRequest($request);
    }

    /**
     * Registers a notification message handler.
     *
     * @param HandlerInterface $handler The handler to register.
     */
    public function registerHandler(HandlerInterface $handler): void
    {
        $this->handlerManager->registerHandler($handler);
    }
}
