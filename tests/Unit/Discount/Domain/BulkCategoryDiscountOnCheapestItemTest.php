<?php

namespace Test\Unit\Discount\Domain;

use App\Customer\Domain\Customer;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Discount\Domain\BulkCategoryDiscountOnCheapestItem;
use App\Discount\Domain\GivenDiscount;
use App\Money\Money;
use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\AugmentedOrderItem;
use App\Order\Domain\ValueObjects\OrderId;
use App\Product\Domain\Product;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BulkCategoryDiscountOnCheapestItemTest extends TestCase
{
    public function testDiscountIsAppliedWhenMinimumQuantityIsMet(): void
    {
        $discount = new BulkCategoryDiscountOnCheapestItem(
            new CategoryId('1'),
            2,
            20
        );

        $order = $this->createOrderWithOrderItems([
            new AugmentedOrderItem(
                new Product(
                    new ProductId('1'),
                    new CategoryId('1'),
                    'Product 1',
                    Money::fromDecimal('10.00', 'EUR'),
                ),
                2,
                Money::fromDecimal('10.00', 'EUR'),
                Money::fromDecimal('20.00', 'EUR')
            ),
            new AugmentedOrderItem(
                new Product(
                    new ProductId('2'),
                    new CategoryId('1'),
                    'Product 2',
                    Money::fromDecimal('20.00', 'EUR'),
                ),
                2,
                Money::fromDecimal('20.00', 'EUR'),
                Money::fromDecimal('40.00', 'EUR')
            ),
        ]);

        $givenDiscount = $discount->apply($order);

        $this->assertInstanceOf(GivenDiscount::class, $givenDiscount);
        $this->assertTrue($givenDiscount->discountValue->equals(Money::fromDecimal('2.00', 'EUR')));
        $this->assertSame('Cheapest product in category gets 20% discount', $givenDiscount->description);
        $this->assertSame([
            'percentage' => 20,
            'minimumQuantity' => 2,
            'productId' => '1',
        ], $givenDiscount->metadata);
    }

    public function testDiscountIsNotAppliedWhenMinimumQuantityIsNotMet(): void
    {
        $discount = new BulkCategoryDiscountOnCheapestItem(
            new CategoryId('1'),
            3,
            20
        );

        $order = $this->createOrderWithOrderItems([
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
        $discount = new BulkCategoryDiscountOnCheapestItem(
            new CategoryId('1'),
            2,
            20
        );

        $order = $this->createOrderWithOrderItems([
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

    /**
     * @param  array<AugmentedOrderItem>  $orderItems
     * @return AugmentedOrder
     */
    private function createOrderWithOrderItems(array $orderItems): AugmentedOrder
    {
        return new AugmentedOrder(
            new OrderId('1'),
            new Customer(
                new CustomerId('1'),
                'John Doe',
                Money::fromDecimal('100.00', 'EUR'),
                new DateTimeImmutable('2024-01-01')
            ),
            Money::fromDecimal('100.00', 'EUR'),
            ...$orderItems
        );
    }
}
