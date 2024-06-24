<?php

namespace App\Order\Application\Service;

use App\Customer\Domain\CustomerRepositoryInterface;
use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\AugmentedOrderItem;
use App\Order\Domain\Order;
use App\Order\Domain\OrderItem;
use App\Product\Domain\ProductRepositoryInterface;

/**
 * Fetches the customer and products for the order and returns an AugmentedOrder (dto) that we can use
 * in the discount calculators.
 */
final readonly class AugmentOrderService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function execute(Order $order): AugmentedOrder
    {
        $orderItems = $order->orderItems;

        $augmentedOrderItems = array_map(
            fn (OrderItem $orderItem) => $this->augmentOrderItem($orderItem),
            $orderItems
        );

        $customer = $this->customerRepository->get($order->customerId);

        return new AugmentedOrder(
            $order->id,
            $customer,
            $order->totalPrice,
            ...$augmentedOrderItems
        );
    }

    private function augmentOrderItem(OrderItem $orderItem): AugmentedOrderItem
    {
        $product = $this->productRepository->get($orderItem->productId);

        return new AugmentedOrderItem(
            product: $product,
            quantity: $orderItem->quantity,
            unitPrice: $orderItem->unitPrice,
            totalPrice: $orderItem->totalPrice
        );
    }
}
