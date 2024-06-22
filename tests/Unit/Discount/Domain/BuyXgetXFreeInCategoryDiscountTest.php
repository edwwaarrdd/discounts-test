<?php

namespace Test\Unit\Discount\Domain;

use App\Discount\Domain\BuyXgetXFreeInCategoryDiscount;
use App\Discount\Domain\GivenDiscount;
use App\Money\Money;
use App\Order\Domain\AugmentedOrderItem;
use App\Product\Domain\Product;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;
use PHPUnit\Framework\TestCase;
use Test\Traits\OrderDataTrait;

class BuyXgetXFreeInCategoryDiscountTest extends TestCase
{
    use OrderDataTrait;

    public function testDiscountIsAppliedWhenBuyQuantityIsMet(): void
    {
        $discount = new BuyXgetXFreeInCategoryDiscount(
            new CategoryId('1'),
            2,
            1
        );

        $order = $this->createAugmentedOrderWithOrderItems([
            new AugmentedOrderItem(
                new Product(
                    new ProductId('1'),
                    new CategoryId('1'),
                    'Product 1',
                    Money::fromDecimal('10.00', 'EUR')
                ),
                3,
                Money::fromDecimal('10.00', 'EUR'),
                Money::fromDecimal('30.00', 'EUR')
            ),
        ]);

        $givenDiscount = $discount->apply($order);

        $this->assertInstanceOf(GivenDiscount::class, $givenDiscount);

        $this->assertTrue($givenDiscount->discountValue->equals(Money::fromDecimal('10.00', 'EUR')));
        $this->assertSame('Buy 2 products in category get 1 free discount', $givenDiscount->description);
        $this->assertSame(
            [
                'categoryId' => '1',
                'buyQuantity' => 2,
                'freeQuantity' => 1,
                'productsThatReceivedFreeItems' => [
                    [
                        'productId' => '1',
                        'quantity' => 1,
                    ],
                ],
            ],
            $givenDiscount->metadata
        );
    }

    public function testDiscountIsAppliedToMultipleProductsWhenBuyQuantityIsMet(): void
    {
        $discount = new BuyXgetXFreeInCategoryDiscount(
            new CategoryId('1'),
            2,
            1
        );

        $order = $this->createAugmentedOrderWithOrderItems([
            new AugmentedOrderItem(
                new Product(
                    new ProductId('1'),
                    new CategoryId('1'),
                    'Product 1',
                    Money::fromDecimal('10.00', 'EUR')
                ),
                3,
                Money::fromDecimal('10.00', 'EUR'),
                Money::fromDecimal('30.00', 'EUR')
            ),
            new AugmentedOrderItem(
                new Product(
                    new ProductId('2'),
                    new CategoryId('1'),
                    'Product 2',
                    Money::fromDecimal('20.00', 'EUR')
                ),
                3,
                Money::fromDecimal('20.00', 'EUR'),
                Money::fromDecimal('60.00', 'EUR')
            ),
        ]);

        $givenDiscount = $discount->apply($order);

        $this->assertInstanceOf(GivenDiscount::class, $givenDiscount);

        $this->assertTrue($givenDiscount->discountValue->equals(Money::fromDecimal('30.00', 'EUR')));
        $this->assertSame('Buy 2 products in category get 1 free discount', $givenDiscount->description);
        $this->assertSame(
            [
                'categoryId' => '1',
                'buyQuantity' => 2,
                'freeQuantity' => 1,
                'productsThatReceivedFreeItems' => [
                    [
                        'productId' => '1',
                        'quantity' => 1,
                    ],
                    [
                        'productId' => '2',
                        'quantity' => 1,
                    ],
                ],
            ],
            $givenDiscount->metadata
        );
    }

    public function testDiscountIsNotAppliedWhenBuyQuantityIsNotMet(): void
    {
        $discount = new BuyXgetXFreeInCategoryDiscount(
            new CategoryId('1'),
            3,
            1
        );

        $order = $this->createAugmentedOrderWithOrderItems([
            new AugmentedOrderItem(
                new Product(
                    new ProductId('1'),
                    new CategoryId('1'),
                    'Product 1',
                    Money::fromDecimal('10.00', 'EUR')
                ),
                2,
                Money::fromDecimal('10.00', 'EUR'),
                Money::fromDecimal('20.00', 'EUR')
            ),
        ]);

        $givenDiscount = $discount->apply($order);

        $this->assertNull($givenDiscount);
    }

    public function testDiscountIsNotAppliedWhenNoItemsFromCategory(): void
    {
        $discount = new BuyXgetXFreeInCategoryDiscount(
            new CategoryId('1'),
            2,
            1
        );

        $order = $this->createAugmentedOrderWithOrderItems([
            new AugmentedOrderItem(
                new Product(
                    new ProductId('1'),
                    new CategoryId('2'),
                    'Product 1',
                    Money::fromDecimal('10.00', 'EUR')
                ),
                2,
                Money::fromDecimal('10.00', 'EUR'),
                Money::fromDecimal('20.00', 'EUR')
            ),
        ]);

        $givenDiscount = $discount->apply($order);

        $this->assertNull($givenDiscount);
    }
}
