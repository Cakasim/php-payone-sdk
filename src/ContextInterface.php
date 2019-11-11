<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

/**
 * The SDK context holds the services of the SDK.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ContextInterface
{
    /**
     * Returns the log service of the SDK.
     *
     * @return Log\Service The Log service.
     */
    public function getLogService(): Log\Service;
}
