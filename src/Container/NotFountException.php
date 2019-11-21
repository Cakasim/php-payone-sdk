<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class NotFountException extends ContainerException implements NotFoundExceptionInterface
{
}
