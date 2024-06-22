<?php

namespace Test\Traits;

use App\Customer\Domain\Customer;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\AugmentedOrderItem;
use App\Order\Domain\ValueObjects\OrderId;
use DateTimeImmutable;

trait OrderDataTrait
{
    /**
     * @param  array<AugmentedOrderItem>  $orderItems
     *
     * @return AugmentedOrder
     */
    private function createAugmentedOrderWithOrderItems(array $orderItems): AugmentedOrder
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

    private function createAugmentedOrderWithCustomer(Customer $customer): AugmentedOrder
    {
        return new AugmentedOrder(
            new OrderId('1'),
            $customer,
            Money::fromDecimal('100.00', 'EUR'),
        );
    }
}
