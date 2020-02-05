<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Log;

/**
 * The SilentLogger does not log anything but provides
 * a working logger implementation. Use this logger
 * if you want to opt-out logging.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class SilentLogger extends AbstractLogger
{
    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        // Keep silence, *psst*
    }
}
