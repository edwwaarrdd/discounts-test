<?php

namespace Test\Unit\Discount\Infrastructure;

use App\Discount\Domain\BulkCategoryDiscountOnCheapestItem;
use App\Discount\Domain\BuyXgetXFreeInCategoryDiscount;
use App\Discount\Domain\LoyaltyDiscount;
use App\Discount\Infrastructure\InMemoryDiscountRepository;
use PHPUnit\Framework\TestCase;

class InMemoryDiscountRepositoryTest extends TestCase
{
    private InMemoryDiscountRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryDiscountRepository();
    }

    public function testDiscountsAreRetrievedCorrectly(): void
    {
        $discounts = $this->repository->getAllActive();

        $this->assertCount(3, $discounts);
        $this->assertInstanceOf(BulkCategoryDiscountOnCheapestItem::class, $discounts[0]);
        $this->assertInstanceOf(BuyXgetXFreeInCategoryDiscount::class, $discounts[1]);
        $this->assertInstanceOf(LoyaltyDiscount::class, $discounts[2]);
    }
}
