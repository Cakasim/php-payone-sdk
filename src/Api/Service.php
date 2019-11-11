<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api;

use Cakasim\Payone\Sdk\AbstractService;
use Cakasim\Payone\Sdk\ContextInterface;

/**
 * The API service.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Service extends AbstractService
{
    /**
     * Constructs the API service.
     *
     * @inheritDoc
     */
    public function __construct(ContextInterface $context)
    {
        parent::__construct($context);
    }
}
