<?php

namespace Test\Unit\Discount\Domain;

use App\Customer\Domain\Customer;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Discount\Domain\GivenDiscount;
use App\Discount\Domain\LoyaltyDiscount;
use App\Money\Money;
use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\ValueObjects\OrderId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Test\Traits\OrderDataTrait;

class LoyaltyDiscountTest extends TestCase
{
    use OrderDataTrait;

    public function testItAppliesLoyaltyDiscountToCustomerIfMinimumRevenueMet(): void
    {
        $discount = new LoyaltyDiscount(
            Money::fromDecimal('1000.00', 'EUR'),
            10
        );

        $order = new AugmentedOrder(
            new OrderId('1'),
            new Customer(
                new CustomerId('1'),
                'John Doe',
                Money::fromDecimal('1001', 'EUR'),
                new DateTimeImmutable('2024-01-01')
            ),
            Money::fromDecimal('105.00', 'EUR'),
        );

        $givenDiscount = $discount->apply($order);

        $this->assertInstanceOf(GivenDiscount::class, $givenDiscount);
        $this->assertSame('Loyalty discount for customer', $givenDiscount->description);
        $this->assertTrue($givenDiscount->discountValue->equals(Money::fromDecimal('10.50', 'EUR')));
        $this->assertSame([
            'percentage' => 10,
            'minimumRevenue' => '1000.00',
        ], $givenDiscount->metadata);
    }

    public function testItDoesNotApplyLoyaltyDiscountToCustomerIfMinimumRevenueNotMet(): void
    {
        $discount = new LoyaltyDiscount(
            Money::fromDecimal('1000.00', 'EUR'),
            10
        );

        $order = $this->createAugmentedOrderWithCustomer(
            new Customer(
                new CustomerId('1'),
                'John Doe',
                Money::fromDecimal('999.99', 'EUR'),
                new DateTimeImmutable('2024-01-01')
            )
        );

        $givenDiscount = $discount->apply($order);

        $this->assertNull($givenDiscount);
    }
}
