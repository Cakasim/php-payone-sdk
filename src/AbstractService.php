<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk;

/**
 * The base class for various SDK services.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
abstract class AbstractService
{
    /**
     * @var ContextInterface The SDK context.
     */
    protected $context;

    /**
     * Constructs the service with the SDK context.
     *
     * @param ContextInterface $context The SDK context instance.
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }
}
