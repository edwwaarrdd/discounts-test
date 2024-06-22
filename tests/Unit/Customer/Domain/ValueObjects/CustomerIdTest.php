<?php

namespace Test\Unit\Customer\Domain\ValueObjects;

use App\Customer\Domain\ValueObjects\CustomerId;
use PHPUnit\Framework\TestCase;

class CustomerIdTest extends TestCase
{
    private CustomerId $customerId;

    protected function setUp(): void
    {
        $this->customerId = new CustomerId('valid-id');
    }

    public function testCustomerIdCanBeCreatedWithValidId(): void
    {
        $this->assertEquals('valid-id', $this->customerId->id);
    }

    public function testCustomerIdMatchesReturnsTrueWhenIdsMatch(): void
    {
        $matchingCustomerId = new CustomerId('valid-id');

        $this->assertTrue($this->customerId->matches($matchingCustomerId));
    }

    public function testCustomerIdMatchesReturnsFalseWhenIdsDoNotMatch(): void
    {
        $nonMatchingCustomerId = new CustomerId('non-matching-id');

        $this->assertFalse($this->customerId->matches($nonMatchingCustomerId));
    }
}
