<?php

namespace App\Order\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Order\Domain\ValueObjects\OrderId;

final class Order
{
    /**
     * @var array<OrderItem>
     */
    public array $orderItems;

    public function __construct(
        public OrderId $id,
        public CustomerId $customerId,
        public Money $totalPrice,
        OrderItem ...$orderItems,
    ) {
        $this->orderItems = $orderItems;
    }
}
