<?php

namespace App\Order\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Order\Domain\ValueObjects\OrderId;
use InvalidArgumentException;
use Throwable;

use function array_map;

final class Order
{
    /**
     * @var OrderItem[]
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

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        try {
            $orderItems = array_map(
                fn (array $item) => OrderItem::fromArray($item),
                $data['items'],
            );

            return new self(
                new OrderId($data['id']),
                new CustomerId($data['customer-id']),
                Money::fromDecimal($data['total'], Money::EUR),
                ...$orderItems
            );
        } catch (Throwable $e) {
            throw new InvalidArgumentException(message: 'Could not parse order data', previous: $e);
        }
    }
}
