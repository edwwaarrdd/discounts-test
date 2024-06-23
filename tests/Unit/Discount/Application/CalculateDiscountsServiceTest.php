<?php

namespace Test\Unit\Discount\Application;

use App\Discount\Application\Service\CalculateDiscountsService;
use App\Discount\Domain\DiscountInterface;
use App\Discount\Domain\DiscountRepositoryInterface;
use App\Discount\Domain\GivenDiscount;
use App\Discount\Domain\TotalDiscount;
use App\Money\Money;
use PHPUnit\Framework\TestCase;
use Test\Traits\OrderDataTrait;

class CalculateDiscountsServiceTest extends TestCase
{
    use OrderDataTrait;

    public function testItCanCalculateTotalDiscountCorrectly(): void
    {
        $discountRepository = $this->createMock(DiscountRepositoryInterface::class);
        $discountCalculator1 = $this->createMock(DiscountInterface::class);
        $discountCalculator2 = $this->createMock(DiscountInterface::class);
        $discountRepository->method('getAllActive')->willReturn([$discountCalculator1, $discountCalculator2]);
        $augmentedOrder = $this->createAugmentedOrder();
        $givenDiscount1 = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR'),
            ['test' => 'metadata']
        );
        $givenDiscount2 = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('5.00', 'EUR'),
            ['test' => 'metadata']
        );
        $discountCalculator1->method('apply')->willReturn($givenDiscount1);
        $discountCalculator2->method('apply')->willReturn($givenDiscount2);

        $service = new CalculateDiscountsService($discountRepository);
        $totalDiscount = $service->execute($augmentedOrder);

        $this->assertEquals(new TotalDiscount($givenDiscount1, $givenDiscount2), $totalDiscount);
    }

    public function testItWillLeaveOutDiscountIfNotApplicable(): void
    {
        $discountRepository = $this->createMock(DiscountRepositoryInterface::class);
        $discountCalculator1 = $this->createMock(DiscountInterface::class);
        $discountCalculator2 = $this->createMock(DiscountInterface::class);
        $discountRepository->method('getAllActive')->willReturn([$discountCalculator1, $discountCalculator2]);
        $augmentedOrder = $this->createAugmentedOrder();
        $givenDiscount1 = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR'),
            ['test' => 'metadata']
        );
        $discountCalculator1->method('apply')->willReturn($givenDiscount1);
        $discountCalculator2->method('apply')->willReturn(null);

        $service = new CalculateDiscountsService($discountRepository);
        $totalDiscount = $service->execute($augmentedOrder);

        $this->assertEquals(new TotalDiscount($givenDiscount1), $totalDiscount);
    }

    public function itCanHandleNoApplicableDiscount(): void
    {
        $discountRepository = $this->createMock(DiscountRepositoryInterface::class);
        $discountCalculator1 = $this->createMock(DiscountInterface::class);
        $discountRepository->method('getAllActive')->willReturn([$discountCalculator1]);
        $augmentedOrder = $this->createAugmentedOrder();
        $discountCalculator1->method('apply')->willReturn(null);

        $service = new CalculateDiscountsService($discountRepository);
        $totalDiscount = $service->execute($augmentedOrder);

        $this->assertEquals(new TotalDiscount(), $totalDiscount);
    }

    public function testItCanHandleNoDiscounts(): void
    {
        $discountRepository = $this->createMock(DiscountRepositoryInterface::class);
        $discountRepository->method('getAllActive')->willReturn([]);
        $augmentedOrder = $this->createAugmentedOrder();

        $service = new CalculateDiscountsService($discountRepository);
        $totalDiscount = $service->execute($augmentedOrder);

        $this->assertEquals(new TotalDiscount(), $totalDiscount);
    }
}
