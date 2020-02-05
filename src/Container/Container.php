<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container;

use Cakasim\Payone\Sdk\Container\Binding\BindingInterface;
use Cakasim\Payone\Sdk\Container\Binding\CallableBinding;
use Cakasim\Payone\Sdk\Container\Binding\ClassBinding;
use Cakasim\Payone\Sdk\Container\Binding\InstanceBinding;
use Psr\Container\ContainerInterface;

/**
 * A PSR-11 container implementation that
 * provides simple yet powerful DI features.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
final class Container implements ContainerInterface
{
    /**
     * @var BindingInterface[] Stores the entries of this container.
     */
    protected $entries = [];

    /**
     * Constructs the Container.
     *
     * @throws ContainerException If the container fails to bind itself.
     */
    public function __construct()
    {
        // Bind the container itself.
        $this->bindInstance(ContainerInterface::class, $this);
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return isset($this->entries[$id]);
    }

    /**
     * Retrieves an entry from the container.
     *
     * @param string $id The entry ID.
     * @return object The container entry.
     *
     * @throws NotFountException If the entry ID does not exist.
     * @throws ContainerException If the entry could not be retrieved.
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFountException("The entry for '{$id}' was not found.");
        }

        $entry = $this->entries[$id];

        if ($entry instanceof BindingInterface) {
            return $entry->resolve();
        }

        throw new ContainerException("Failed to get entry for '{$id}'.");
    }

    /**
     * Binds a concrete class to the provided ID.
     *
     * @param string $id The abstract type of the provided concrete.
     * @param string|null $concrete The concrete type or null if the provided ID should be used as concrete type.
     * @param bool $singleton True if the binding is a singleton.
     * @return $this
     *
     * @throws ContainerException If the binding fails.
     */
    public function bind(string $id, string $concrete = null, bool $singleton = false): self
    {
        $this->throwIfBindingToExistingEntry($id);

        if ($concrete === null) {
            $concrete = $id;
        }

        $this->entries[$id] = new ClassBinding($this, $id, $concrete, $singleton);
        return $this;
    }

    /**
     * Binds an instance to the provided ID.
     *
     * @param string $id The abstract type of the provided instance.
     * @param object $concrete The instance to bind to the ID.
     * @return $this
     *
     * @throws ContainerException If the binding fails.
     */
    public function bindInstance(string $id, $concrete): self
    {
        $this->throwIfBindingToExistingEntry($id);

        $this->entries[$id] = new InstanceBinding($id, $concrete);
        return $this;
    }

    /**
     * Binds a callable to the provided ID.
     * The callable must return an instance that is compatible
     * to the provided ID.
     *
     * @param string $id The abstract type of the callable's return value.
     * @param callable $concrete The callable which returns the actual binding.
     * @param bool $singleton True if the binding is a singleton.
     * @return $this
     *
     * @throws ContainerException If binding fails.
     */
    public function bindCallable(string $id, callable $concrete, bool $singleton = false): self
    {
        $this->throwIfBindingToExistingEntry($id);

        $this->entries[$id] = new CallableBinding($this, $id, $concrete, $singleton);
        return $this;
    }

    /**
     * Prevents binding to an already bound ID.
     *
     * @param string $id The identifier to check.
     *
     * @throws ContainerException If the provided ID is already bound.
     */
    protected function throwIfBindingToExistingEntry(string $id): void
    {
        if ($this->has($id)) {
            throw new ContainerException("Failed to bind to identifier '{$id}', the identifier is already bound.");
        }
    }
}
