<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container\Binding;

use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;
use ReflectionException;
use ReflectionFunction;

/**
 * A binding that uses a callable to resolve
 * to the underlying value.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class CallableBinding implements BindingInterface
{
    use ProvidesParameterDi;

    /**
     * @var Container The container that uses this binding.
     */
    protected $container;

    /**
     * @var string The class / interface type the concrete is bound to.
     */
    protected $abstract;

    /**
     * @var ReflectionFunction The reflection of the callable that returns the underlying value.
     */
    protected $concrete;

    /**
     * @var string[] The callable's parameter types.
     */
    protected $parameters = [];

    /**
     * @var bool|mixed Whether a singleton should be used or the singleton itself.
     */
    protected $singleton;

    /**
     * Constructs the CallableBinding.
     *
     * @param Container $container The container that uses this binding.
     * @param string $abstract The class / interface type the concrete is bound to.
     * @param callable $concrete The callable that returns the underlying value.
     * @param bool $singleton Whether or not to use the underlying value as singleton.
     *
     * @throws ContainerException If the provided callable does not satisfy the requirements.
     */
    public function __construct(Container $container, string $abstract, callable $concrete, bool $singleton)
    {
        $this->container = $container;
        $this->abstract = $abstract;
        $this->singleton = $singleton;

        try {
            $this->concrete = new ReflectionFunction($concrete);
        } catch (ReflectionException $e) {
            throw new ContainerException("Cannot create binding for '{$abstract}', failed to create reflection of the callable.");
        }

        if (!$this->validateDiParameters($this->concrete->getParameters(), $this->parameters)) {
            throw new ContainerException("Cannot create binding for '{$abstract}', all parameters of the callable must be type hinted with classes.");
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
        $concrete = $this->concrete->invokeArgs($parameters);

        if (!is_object($concrete)) {
            throw new ContainerException("Failed resolving of callable bound to '{$this->abstract}', the callable must return an object.");
        }

        if (!($concrete instanceof $this->abstract)) {
            throw new ContainerException("Failed resolving of callable bound to '{$this->abstract}', the return value must be an instance of '{$this->abstract}'.");
        }

        if ($this->singleton === true) {
            $this->singleton = $concrete;
        }

        return $concrete;
    }
}
