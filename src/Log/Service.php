<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Log;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\ContextInterface;
use Psr\Log\LoggerInterface;

/**
 * The log service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructs the log service.
     *
     * @param LoggerInterface $logger The logger which should be used by the SDK.
     * @inheritDoc
     */
    public function __construct(ContextInterface $context, LoggerInterface $logger)
    {
        parent::__construct($context);
        $this->logger = $logger;
    }

    /**
     * Returns the SDK logger instance.
     *
     * @return LoggerInterface The SDK logger instance.
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
