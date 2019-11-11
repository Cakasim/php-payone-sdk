<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

/**
 * The default implementation of the ContextInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Context implements ContextInterface
{
    /**
     * @var Log\Service The log service instance.
     */
    protected $logService;

    /**
     * @var Http\Service The HTTP service instance.
     */
    protected $httpService;

    /**
     * @var Api\Service The API service instance.
     */
    protected $apiService;

    /**
     * @inheritDoc
     */
    public function getLogService(): Log\Service
    {
        return $this->logService;
    }

    /**
     * Sets the log service to use.
     *
     * @param Log\Service $service The log service instance.
     */
    public function setLogService(Log\Service $service): void
    {
        $this->logService = $service;
    }

    /**
     * @inheritDoc
     */
    public function getHttpService(): Http\Service
    {
        return $this->httpService;
    }

    /**
     * Sets the HTTP service to use.
     *
     * @param Http\Service $service The HTTP service instance.
     */
    public function setHttpService(Http\Service $service): void
    {
        $this->httpService = $service;
    }

    /**
     * @inheritDoc
     */
    public function getApiService(): Api\Service
    {
        return $this->apiService;
    }

    /**
     * Sets the API service to use.
     *
     * @param Api\Service $service The API service instance.
     */
    public function setApiService(Api\Service $service): void
    {
        $this->apiService = $service;
    }
}
