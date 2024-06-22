<?php

namespace Test\Unit\Discount\Domain;

use App\Discount\Domain\GivenDiscount;
use App\Money\Money;
use PHPUnit\Framework\TestCase;

class GivenDiscountTest extends TestCase
{
    public function testGivenDiscountCanBeCreated(): void
    {
        $discount = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR'),
            ['test' => 'metadata']
        );

        $this->assertInstanceOf(GivenDiscount::class, $discount);
    }

    public function testGivenDiscountCanBeConvertedToArray(): void
    {
        $discount = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR'),
            ['test' => 'metadata']
        );

        $expectedArray = [
            'description' => 'Test discount',
            'discountValue' => '10.00',
            'metadata' => ['test' => 'metadata'],
        ];

        $this->assertEquals($expectedArray, $discount->toArray());
    }

    public function testGivenDiscountWithoutMetadataCanBeConvertedToArray(): void
    {
        $discount = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR')
        );

        $expectedArray = [
            'description' => 'Test discount',
            'discountValue' => '10.00',
            'metadata' => [],
        ];

        $this->assertEquals($expectedArray, $discount->toArray());
    }
}
