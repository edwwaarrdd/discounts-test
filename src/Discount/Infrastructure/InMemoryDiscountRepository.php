<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\BulkCategoryDiscountOnCheapestItem;
use App\Discount\Domain\BuyXgetXFreeInCategoryDiscount;
use App\Discount\Domain\DiscountRepositoryInterface;
use App\Discount\Domain\LoyaltyDiscount;
use App\Money\Money;
use App\Product\Domain\ValueObjects\CategoryId;

class InMemoryDiscountRepository implements DiscountRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAllActive(): array
    {
        return [
            new BulkCategoryDiscountOnCheapestItem(
                new CategoryId('1'),
                2,
                20
            ),
            new BuyXgetXFreeInCategoryDiscount(
                new CategoryId('2'),
                5,
                1
            ),
            new LoyaltyDiscount(
                Money::fromDecimal('1000', Money::EUR),
                10
            ),
        ];
    }
}
