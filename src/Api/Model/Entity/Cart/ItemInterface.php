<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Api\Model\Entity\Cart;

/**
 * An interface for items.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
interface ItemInterface
{
    public const TYPE_GOODS = 'goods';
    public const TYPE_SHIPMENT = 'shipment';
    public const TYPE_HANDLING = 'handling';
    public const TYPE_VOUCHER = 'voucher';

    /**
     * Returns the ID of this item.
     * You may use any ID value you like for this.
     * In general something like the product SKU
     * would be good choice.
     *
     * @return string|null The item ID or null if not set.
     */
    public function getId(): ?string;

    /**
     * Returns the type of this item.
     * The item type should be one of goods, shipment,
     * handling or voucher.
     *
     * @return string|null The item type or null if not set.
     */
    public function getType(): ?string;

    /**
     * Returns the description of this item.
     * It is a good practice to use a short descriptive
     * name of your product as item description.
     *
     * @return string|null The item description or null if not set.
     */
    public function getDescription(): ?string;

    /**
     * Returns the price of this item in the smallest
     * unit of the currency (e.g. for EUR this is cent).
     *
     * @return int|null The item price or null if not set.
     */
    public function getPrice(): ?int;

    /**
     * Returns the VAT of this item.
     * If the VAT value is <= 99 it indicates a percent value.
     * Values > 99 represent the factor 100 of actual percent values.
     * For example: 1900 represents 19 % VAT.
     * It is recommended to only use values > 99 for consistency reasons.
     *
     * @return int|null The item VAT or null if not set.
     */
    public function getVat(): ?int;

    /**
     * Returns the quantity of this item.
     *
     * @return int|null The item quantity or null if not set.
     */
    public function getQuantity(): ?int;

    /**
     * Returns the start delivery date of this item.
     *
     * @return string|null The start delivery date in format YYYYMMDD.
     */
    public function getStartDelivery(): ?string;

    /**
     * Returns the end delivery date of this item.
     *
     * @return string|null The end delivery date in format YYYYMMDD.
     */
    public function getEndDelivery(): ?string;
}
