<?php

namespace App\Order\Domain;

use App\Customer\Domain\Customer;
use App\Money\Money;
use App\Order\Domain\ValueObjects\OrderId;
use App\Product\Domain\ValueObjects\CategoryId;

final class AugmentedOrder
{
    /**
     * @var array<AugmentedOrderItem>
     */
    public array $orderItems;

    public function __construct(
        public OrderId $id,
        public Customer $customer,
        public Money $totalPrice,
        AugmentedOrderItem ...$orderItems,
    ) {
        $this->orderItems = $orderItems;
    }

    /**
     * @param  CategoryId  $category
     *
     * @return array<AugmentedOrderItem>
     */
    public function getOrderItemsOfCategory(CategoryId $category): array
    {
        return array_filter(
            $this->orderItems,
            fn (AugmentedOrderItem $orderItem) => $orderItem->product->categoryId->matches($category)
        );
    }
}
