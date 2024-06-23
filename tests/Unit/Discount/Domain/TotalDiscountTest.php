<?php

namespace Test\Unit\Discount\Domain;

use App\Discount\Domain\GivenDiscount;
use App\Discount\Domain\TotalDiscount;
use App\Money\Money;
use PHPUnit\Framework\TestCase;

class TotalDiscountTest extends TestCase
{
    public function testItCanCalculateTheTotalAmount(): void
    {
        $discount = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('10.00', 'EUR'),
            ['test' => 'metadata']
        );
        $discount2 = new GivenDiscount(
            'Test discount',
            Money::fromDecimal('5.00', 'EUR'),
            ['test' => 'metadata']
        );
        $totalDiscount = new TotalDiscount($discount, $discount2);

        $this->assertTrue($totalDiscount->getTotal()->equals(Money::fromDecimal('15.00', 'EUR')));
    }
}
