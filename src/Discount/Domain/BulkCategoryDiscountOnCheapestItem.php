<?php

namespace App\Discount\Domain;

use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\AugmentedOrderItem;
use App\Product\Domain\ValueObjects\CategoryId;

final readonly class BulkCategoryDiscountOnCheapestItem implements DiscountInterface
{
    public function __construct(
        private CategoryId $categoryId,
        private int $minimumQuantity,
        private int $percentage
    ) {
    }

    public function apply(AugmentedOrder $order): ?GivenDiscount
    {
        $categoryItems = $order->getOrderItemsOfCategory($this->categoryId);

        if (empty($categoryItems)) {
            return null;
        }

        $totalItems = array_reduce(
            $categoryItems,
            fn (int $carry, AugmentedOrderItem $orderItem) => $carry + $orderItem->quantity,
            0
        );

        if ($totalItems < $this->minimumQuantity) {
            return null;
        }

        $cheapestItem = $categoryItems[0];
        foreach ($categoryItems as $categoryItem) {
            if (!$categoryItem->unitPrice->isMoreThan($cheapestItem->unitPrice)) {
                $cheapestItem = $categoryItem;
            }
        }

        $discountValue = $cheapestItem->unitPrice->multiply((string)($this->percentage / 100));

        return new GivenDiscount(
            description: "Cheapest product in category gets $this->percentage% discount",
            discountValue: $discountValue,
            metadata: [
                'percentage' => $this->percentage,
                'minimumQuantity' => $this->minimumQuantity,
                'productId' => $cheapestItem->product->id->value,
            ]
        );
    }
}
