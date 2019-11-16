<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Model\Entity;

use Cakasim\Payone\Sdk\Api\Model\Entity\Cart\Cart;
use Cakasim\Payone\Sdk\Api\Model\Entity\Cart\CartInterface;
use Cakasim\Payone\Sdk\Api\Model\Entity\Cart\Item;
use Cakasim\Payone\Sdk\Api\Model\Entity\Cart\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class CartTest extends TestCase
{
    /**
     * Performs general cart checks.
     */
    public function testCartGeneralChecks(): void
    {
        $cart = new Cart();
        $this->assertInstanceOf(CartInterface::class, $cart);
        $this->assertFalse($cart->hasItems());
        $this->assertEmpty($cart->getItems());
    }

    /**
     * Performs general item checks.
     */
    public function testItemGeneralChecks(): void
    {
        $item = new Item();
        $this->assertInstanceOf(ItemInterface::class, $item);
        $this->assertNull($item->getId());
        $this->assertNull($item->getType());
        $this->assertNull($item->getDescription());
        $this->assertNull($item->getPrice());
        $this->assertNull($item->getVat());
        $this->assertNull($item->getQuantity());
        $this->assertNull($item->getStartDelivery());
        $this->assertNull($item->getEndDelivery());

        $this->assertSame($item, $item->setId('P12345'));
        $this->assertEquals('P12345', $item->getId());
        $this->assertNull($item->setId(null)->getId());

        $this->assertSame($item, $item->setType(Item::TYPE_GOODS));
        $this->assertEquals(Item::TYPE_GOODS, $item->getType());
        $this->assertNull($item->setType(null)->getType());

        $this->assertSame($item, $item->setDescription('Cox Apple'));
        $this->assertEquals('Cox Apple', $item->getDescription());
        $this->assertNull($item->setDescription(null)->getDescription());

        $this->assertSame($item, $item->setPrice(150));
        $this->assertEquals(150, $item->getPrice());
        $this->assertNull($item->setPrice(null)->getPrice());

        $this->assertSame($item, $item->setVat(700));
        $this->assertEquals(700, $item->getVat());
        $this->assertNull($item->setVat(null)->getVat());

        $this->assertSame($item, $item->setQuantity(3));
        $this->assertEquals(3, $item->getQuantity());
        $this->assertNull($item->setQuantity(null)->getQuantity());

        $this->assertSame($item, $item->setStartDelivery('20221224'));
        $this->assertEquals('20221224', $item->getStartDelivery());
        $this->assertNull($item->setStartDelivery(null)->getStartDelivery());

        $this->assertSame($item, $item->setEndDelivery('20231224'));
        $this->assertEquals('20231224', $item->getEndDelivery());
        $this->assertNull($item->setEndDelivery(null)->getEndDelivery());
    }

    /**
     * Tests the cart with items.
     */
    public function testCartWithItems(): void
    {
        $cart = new Cart();

        $apple = (new Item())
            ->setId('P-APL-1')
            ->setType(Item::TYPE_GOODS)
            ->setDescription('Cox Apple')
            ->setPrice(150)
            ->setVat(700)
            ->setQuantity(2);

        $orange = (new Item())
            ->setId('P-ORA-1')
            ->setType(Item::TYPE_GOODS)
            ->setDescription('Juicy Orange')
            ->setPrice(120)
            ->setVat(700)
            ->setQuantity(5);

        $shipment = (new Item())
            ->setId('S-DEF-1')
            ->setType(Item::TYPE_SHIPMENT)
            ->setDescription('Shipment Standard Rate')
            ->setPrice(120)
            ->setVat(700)
            ->setQuantity(5);

        $voucher = (new Item())
            ->setId('V-HAPPYDAY')
            ->setType(Item::TYPE_VOUCHER)
            ->setDescription('HAPPY DAY Coupon')
            ->setPrice(-500)
            ->setVat(0)
            ->setQuantity(1);

        $cart
            ->addItem($apple)
            ->addItem($orange)
            ->addItem($shipment)
            ->addItem($voucher);

        $this->assertNotEmpty($cart->getItems());
        $this->assertCount(4, $cart->getItems());

        foreach ($cart->getItems() as $item) {
            $this->assertInstanceOf(Item::class, $item);
        }
    }
}
