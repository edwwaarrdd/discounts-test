<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\BulkCategoryDiscountOnCheapestItem;
use App\Discount\Domain\BuyXGetXFreeInCategoryDiscount;
use App\Discount\Domain\DiscountRepositoryInterface;
use App\Discount\Domain\LoyaltyDiscount;
use App\Money\Money;
use App\Product\Domain\ValueObjects\CategoryId;

class InMemoryDiscountRepository implements DiscountRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllActive(): array
    {
        return [
            new BulkCategoryDiscountOnCheapestItem(
                categoryId: new CategoryId('1'),
                minimumQuantity: 2,
                percentage: 20
            ),
            new BuyXGetXFreeInCategoryDiscount(
                categoryId: new CategoryId('2'),
                buyQuantity: 5,
                freeQuantity: 1
            ),
            new LoyaltyDiscount(
                minimumRevenue: Money::fromDecimal('1000', Money::EUR),
                percentage: 10
            ),
        ];
    }
}
