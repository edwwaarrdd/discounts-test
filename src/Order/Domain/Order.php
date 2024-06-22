<?php

namespace App\Order\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Order\Domain\ValueObjects\OrderId;
use App\Product\Domain\ValueObjects\ProductId;

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

    public static function fromArray(array $data): self
    {
        $orderItems = array_map(
            fn(array $item) => OrderItem::fromArray($item),
            $data['items'],
        );

        return new self(
            new OrderId($data['id']),
            new CustomerId($data['customer-id']),
            Money::fromDecimal($data['total'], Money::EUR),
            ...$orderItems
        );
    }
}
