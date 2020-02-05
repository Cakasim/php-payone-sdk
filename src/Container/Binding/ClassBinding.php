<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container\Binding;

use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;
use ReflectionClass;
use ReflectionException;

/**
 * A binding that uses a class whose instance
 * will be the the underlying value.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ClassBinding implements BindingInterface
{
    use ProvidesParameterDi;

    /**
     * @var Container The container that uses this binding.
     */
    protected $container;

    /**
     * @var ReflectionClass The reflection of the concrete class whose instance is the underlying value.
     */
    protected $concrete;

    /**
     * @var string[] The concrete class constructor's parameter types.
     */
    protected $parameters = [];

    /**
     * @var bool|mixed Whether a singleton should be used or the singleton itself.
     */
    protected $singleton;

    /**
     * Constructs the ClassBinding.
     *
     * @param Container $container The container that uses this binding.
     * @param string $abstract The class / interface type the concrete is bound to.
     * @param string $concrete The class type which is the concrete of the abstract.
     * @param bool $singleton Whether or not to use the underlying value as singleton.
     *
     * @throws ContainerException If the provided abstract or concrete do not satisfy the requirements.
     */
    public function __construct(Container $container, string $abstract, string $concrete, bool $singleton)
    {
        $this->container = $container;
        $this->singleton = $singleton;

        if (!interface_exists($abstract) && !class_exists($abstract)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the type '{$abstract}' does not exist.");
        }

        try {
            $this->concrete = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the type '{$concrete}' does not exist.", $e);
        }

        if ($concrete !== $abstract && !$this->concrete->isSubclassOf($abstract)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the type '{$concrete}' must be an instance of '{$abstract}'.");
        }

        if (!$this->concrete->isInstantiable()) {
            throw new ContainerException("Cannot create binding for '{$abstract}', the type '{$concrete}' must be instantiable.");
        }

        $constructor = $this->concrete->getConstructor();

        if ($constructor && !$this->validateDiParameters($constructor->getParameters(), $this->parameters)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', all constructor parameters of '{$concrete}' must have class type hints.");
        }
    }

    /**
     * @throws ContainerException If resolving fails.
     * @inheritDoc
     */
    public function resolve()
    {
        if ($this->singleton && $this->singleton !== true) {
            return $this->singleton;
        }

        $parameters = $this->resolveDiParameters($this->container, $this->parameters);
        $concrete = $this->concrete->newInstanceArgs($parameters);

        if ($this->singleton === true) {
            $this->singleton = $concrete;
        }

        return $concrete;
    }
}
