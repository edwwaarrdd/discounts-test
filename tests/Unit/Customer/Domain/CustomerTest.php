<?php

namespace Test\Unit\Customer\Domain;

use App\Customer\Domain\Customer;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Product\Domain\ValueObjects\CategoryId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        $this->customer = new Customer(
            id: new CustomerId('valid-id'),
            name: 'customer a',
            revenue: Money::fromDecimal('100', Money::EUR),
            customerSince: DateTimeImmutable::createFromFormat('Y-m-d', '2024-01-01')
        );
    }

    public function testCustomerCanBeCreatedWithValidParameters(): void
    {
        $this->assertTrue($this->customer->id->matches(new CustomerId('valid-id')));
        $this->assertEquals('customer a', $this->customer->name);
        $this->assertTrue($this->customer->revenue->equals(Money::fromDecimal('100', Money::EUR)));
        $this->assertEquals('2024-01-01', $this->customer->customerSince->format('Y-m-d'));
    }
}
