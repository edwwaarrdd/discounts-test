<?php

namespace App\Discount\Domain;

use App\Order\Domain\AugmentedOrder;

interface DiscountInterface
{
    public function apply(AugmentedOrder $order): ?GivenDiscount;
}
