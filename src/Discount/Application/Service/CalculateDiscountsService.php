<?php

namespace App\Discount\Application\Service;

use App\Discount\Domain\DiscountRepositoryInterface;
use App\Discount\Domain\TotalDiscount;
use App\Order\Domain\AugmentedOrder;

use function array_filter;
use function array_values;

final readonly class CalculateDiscountsService
{
    public function __construct(private DiscountRepositoryInterface $discountRepository)
    {
    }

    public function execute(AugmentedOrder $augmentedOrder): TotalDiscount
    {
        $discounts = $this->discountRepository->getAllActive();

        $appliedDiscounts = [];

        foreach ($discounts as $discount) {
            $appliedDiscounts[] = $discount->apply($augmentedOrder);
        }

        // Filter out empty discounts and reset the keys
        return new TotalDiscount(...array_values(array_filter($appliedDiscounts)));
    }
}
