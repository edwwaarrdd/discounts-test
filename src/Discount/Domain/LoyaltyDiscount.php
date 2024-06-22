<?php

namespace App\Discount\Domain;

use App\Money\Money;
use App\Order\Domain\AugmentedOrder;

readonly class LoyaltyDiscount implements DiscountInterface
{
    public function __construct(
        private Money $minimumRevenue,
        private int $percentage
    ) {

    }

    public function apply(AugmentedOrder $order): ?GivenDiscount
    {
        $customerTotalRevenue = $order->customer->revenue;

        if (! $customerTotalRevenue->isMoreThan($this->minimumRevenue)) {
            return null;
        }

        $discountValue = $order->totalPrice->multiply($this->percentage / 100);

        return new GivenDiscount(
            description: 'Loyalty discount for customer',
            discountValue: $discountValue,
            metadata: [
                'percentage' => $this->percentage,
                'minimumRevenue' => $this->minimumRevenue->toDecimal(),
            ]
        );
    }
}
