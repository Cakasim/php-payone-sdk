<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Client;

use Cakasim\Payone\Sdk\SdkExceptionInterface;
use Exception;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ClientException extends Exception implements ClientExceptionInterface, SdkExceptionInterface
{
}
