<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Entity\Cart;

/**
 * The implementation of the ItemInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Item implements ItemInterface
{
    /**
     * @var string|null The item ID.
     */
    protected $id;

    /**
     * @var string|null The item type.
     */
    protected $type;

    /**
     * @var string|null The item description.
     */
    protected $description;

    /**
     * @var int|null The item price.
     */
    protected $price;

    /**
     * @var int|null The item VAT.
     */
    protected $vat;

    /**
     * @var int|null The item quantity.
     */
    protected $quantity;

    /**
     * @var string|null The start delivery date in format YYYYMMDD.
     */
    protected $startDelivery;

    /**
     * @var string|null The end delivery date in format YYYYMMDD.
     */
    protected $endDelivery;

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the ID of this item.
     *
     * @param string|null $id The item ID.
     * @return $this
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of this item.
     *
     * @param string|null $type The item type.
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of this item.
     *
     * @param string|null $description The item description.
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Sets the price of this item.
     *
     * @param int|null $price The item price.
     * @return $this
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVat(): ?int
    {
        return $this->vat;
    }

    /**
     * Sets the VAT of this item.
     *
     * @param int|null $vat The item VAT.
     * @return $this
     */
    public function setVat(?int $vat): self
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * Sets the quantity of this item.
     *
     * @param int|null $quantity The item quantity.
     * @return $this
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStartDelivery(): ?string
    {
        return $this->startDelivery;
    }

    /**
     * Sets the start delivery date of this item.
     *
     * @param string|null $startDelivery The item start delivery date in format YYYYMMDD.
     * @return $this
     */
    public function setStartDelivery(?string $startDelivery): self
    {
        $this->startDelivery = $startDelivery;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEndDelivery(): ?string
    {
        return $this->endDelivery;
    }

    /**
     * Sets the end delivery date of this item.
     *
     * @param string|null $endDelivery The item end delivery date in format YYYYMMDD.
     * @return $this
     */
    public function setEndDelivery(?string $endDelivery): self
    {
        $this->endDelivery = $endDelivery;
        return $this;
    }
}
