<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Entity\Cart;

/**
 * An interface for carts that have items.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface CartInterface
{
    /**
     * Returns whether this cart contains items or not.
     *
     * @return bool True if this cart is not empty.
     */
    public function hasItems(): bool;

    /**
     * Returns the items of this cart.
     *
     * @return ItemInterface[] An item collection of the items in this cart.
     */
    public function getItems(): iterable;
}
