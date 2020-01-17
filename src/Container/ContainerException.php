<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container;

use Cakasim\Payone\Sdk\SdkExceptionInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ContainerException extends Exception implements ContainerExceptionInterface, SdkExceptionInterface
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
