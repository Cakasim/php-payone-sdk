<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container\Binding;

use Cakasim\Payone\Sdk\Container\ContainerException;

/**
 * A binding that uses an instance as
 * underlying value.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class InstanceBinding implements BindingInterface
{
    /**
     * @var object The instance to use as underlying value.
     */
    protected $concrete;

    /**
     * Constructs the InstanceBinding.
     *
     * @param string $abstract The class / interface type the concrete is bound to.
     * @param object $concrete The instance which is a concrete of abstract.
     *
     * @throws ContainerException If concrete does not satisfy the requirements.
     */
    public function __construct(string $abstract, $concrete)
    {
        if (!is_object($concrete)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the provided concrete must be an object.");
        }

        if (!($concrete instanceof $abstract)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the provided concrete must be an instance of '{$abstract}'.");
        }

        $this->concrete = $concrete;
    }

    /**
     * @inheritDoc
     */
    public function resolve()
    {
        return $this->concrete;
    }
}
