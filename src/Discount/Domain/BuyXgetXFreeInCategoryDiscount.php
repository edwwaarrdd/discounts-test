<?php

namespace App\Discount\Domain;

use App\Money\Money;
use App\Order\Domain\AugmentedOrder;
use App\Product\Domain\ValueObjects\CategoryId;

readonly class BuyXgetXFreeInCategoryDiscount implements DiscountInterface
{
    public function __construct(
        private CategoryId $categoryId,
        private int $buyQuantity,
        private int $freeQuantity
    ) {
    }

    public function apply(AugmentedOrder $order): ?GivenDiscount
    {
        $categoryItems = $order->getOrderItemsOfCategory($this->categoryId);

        if (empty($categoryItems)) {
            return null;
        }

        $totalDiscountValue = Money::zero(Money::EUR);
        $productsThatReceivedFreeItems = [];

        foreach ($categoryItems as $categoryItem) {
            if ($categoryItem->quantity < $this->buyQuantity) {
                continue;
            }

            //Calculate how many free items to give.
            $discountTimes = floor($categoryItem->quantity / ($this->buyQuantity + $this->freeQuantity));
            $freeItems = (int) ($discountTimes * $this->freeQuantity);

            if ($freeItems === 0) {
                continue;
            }

            $totalDiscountValue = $totalDiscountValue->add($categoryItem->unitPrice->multiply($freeItems));
            $productsThatReceivedFreeItems[] = [
                'productId' => $categoryItem->product->id->id,
                'quantity' => $freeItems,
            ];
        }

        if ($totalDiscountValue->isZero()) {
            return null;
        }

        return new GivenDiscount(
            description: "Buy $this->buyQuantity products in category get $this->freeQuantity free discount",
            discountValue: $totalDiscountValue,
            metadata: [
                'categoryId' => $this->categoryId->id,
                'buyQuantity' => $this->buyQuantity,
                'freeQuantity' => $this->freeQuantity,
                'productsThatReceivedFreeItems' => $productsThatReceivedFreeItems,
            ]
        );
    }
}
