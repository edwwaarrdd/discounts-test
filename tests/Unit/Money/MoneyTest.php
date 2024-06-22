<?php

namespace Test\Unit\Money;

use App\Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testMoneyCanBeCreatedFromDecimal(): void
    {
        $money = Money::fromDecimal('10.00', 'EUR');
        $this->assertInstanceOf(Money::class, $money);
    }

    public function testMoneyEqualityWorksCorrectly(): void
    {
        $money1 = Money::fromDecimal('10.00', 'EUR');
        $money2 = Money::fromDecimal('10.00', Money::EUR);
        $this->assertTrue($money1->equals($money2));
    }

    public function testMoneyInequalityWorksCorrectly(): void
    {
        $money1 = Money::fromDecimal('10.00', 'EUR');
        $money2 = Money::fromDecimal('20.00', 'EUR');
        $this->assertFalse($money1->equals($money2));
    }

    public function testMoneyToDecimalWorksCorrectly(): void
    {
        $money = Money::fromDecimal('10.00', 'EUR');
        $this->assertEquals('10.00', $money->toDecimal());
    }

    public function testMoneyToDecimalWorksCorrectlyWithDifferentValues(): void
    {
        $money = Money::fromDecimal('1234.56', 'EUR');
        $this->assertEquals('1234.56', $money->toDecimal());
    }

    public function testZeroMoneyCanBeCreated(): void
    {
        $money = Money::zero('EUR');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertTrue($money->isZero());
    }

    public function testMoneyComparisonWorksCorrectly(): void
    {
        $money1 = Money::fromDecimal('10.00', 'EUR');
        $money2 = Money::fromDecimal('20.00', 'EUR');
        $this->assertTrue($money2->isMoreThan($money1));
    }

    public function testMoneyMultiplicationWorksCorrectly(): void
    {
        $money = Money::fromDecimal('10.00', 'EUR');
        $multipliedMoney = $money->multiply(2);
        $this->assertEquals('20.00', $multipliedMoney->toDecimal());
    }

    public function testMoneyAdditionWorksCorrectly(): void
    {
        $money1 = Money::fromDecimal('10.00', 'EUR');
        $money2 = Money::fromDecimal('20.00', 'EUR');
        $addedMoney = $money1->add($money2);
        $this->assertEquals('30.00', $addedMoney->toDecimal());
    }

    public function testIsZeroWorksCorrectly(): void
    {
        $money = Money::fromDecimal('0.00', 'EUR');
        $this->assertTrue($money->isZero());
    }
}
