<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container\Binding;

/**
 * Defines an interface for container entry bindings.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface BindingInterface
{
    /**
     * Resolves the binding to the underlying value.
     *
     * @return mixed The underlying value of this binding.
     */
    public function resolve();
}
