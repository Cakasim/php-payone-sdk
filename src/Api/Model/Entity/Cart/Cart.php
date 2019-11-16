<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Entity\Cart;

/**
 * The implementation of the CartInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Cart implements CartInterface
{
    /**
     * @var ItemInterface[] The items of this cart.
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function hasItems(): bool
    {
        return !empty($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getItems(): iterable
    {
        return $this->items;
    }

    /**
     * Adds an item to this cart.
     *
     * @param ItemInterface $item The item to add.
     * @return $this
     */
    public function addItem(ItemInterface $item): self
    {
        $this->items[] = $item;
        return $this;
    }
}
