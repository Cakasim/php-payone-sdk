<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Container\Binding;

use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Container\ContainerException;
use ReflectionParameter;

/**
 * This trait adds methods for bindings that use parameter DI.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
trait ProvidesParameterDi
{
    /**
     * Checks whether a parameter list is DI compatible.
     *
     * @param ReflectionParameter[] $parameters The parameter list to check.
     * @param array $classes A reference to an array tat will be filled with the parameter class / interface types
     * @return bool True if the parameter list is DI compatible.
     */
    protected function validateDiParameters(array $parameters, array &$classes): bool
    {
        foreach ($parameters as $parameter) {
            // Get class type hint of the parameter,
            // null if no such type hint is present.
            $parameter = $parameter->getClass();

            // Return false if the parameter has no class type hint.
            if (!$parameter) {
                return false;
            }

            $classes[] = $parameter->getName();
        }

        return true;
    }

    /**
     * Resolves the provided parameters from the
     * provided container.
     *
     * @param Container $container The container to use for parameter resolving.
     * @param string[] $parameters The parameters class / interface types to resolve.
     * @return array The resolved parameters.
     *
     * @throws ContainerException If the resolving fails.
     */
    protected function resolveDiParameters(Container $container, array $parameters): array
    {
        $resultParameters = [];

        try {
            foreach ($parameters as $parameter) {
                $resultParameters[] = $container->get($parameter);
            }
        } catch (ContainerException $e) {
            throw new ContainerException("Failed resolving of DI parameters.", $e);
        }

        return $resultParameters;
    }
}
