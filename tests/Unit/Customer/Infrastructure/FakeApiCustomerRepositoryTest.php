<?php

namespace Test\Unit\Customer\Infrastructure;

use App\Customer\Domain\CustomerNotFoundException;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Customer\Infrastructure\FakeApiCustomerRepository;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FakeApiCustomerRepositoryTest extends TestCase
{
    private FakeApiCustomerRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new FakeApiCustomerRepository();
    }

    public function testGetCustomerWithValidId(): void
    {
        $customerId = new CustomerId($this->repository::VALID_CUSTOMER_ID);

        $customer = $this->repository->get($customerId);

        $this->assertEquals($customerId, $customer->id);
    }

    public function testGetCustomerWithInvalidIdThrowsException(): void
    {
        $this->expectExceptionObject(
            new CustomerNotFoundException(new CustomerId($this->repository::INVALID_CUSTOMER_ID))
        );

        $customerId = new CustomerId($this->repository::INVALID_CUSTOMER_ID);
        $this->repository->get($customerId);
    }

    public function testMapCustomersFromJsonFileReturnsCorrectNumberOfCustomers(): void
    {
        $reflection = new ReflectionClass(FakeApiCustomerRepository::class);
        $method = $reflection->getMethod('mapCustomersFromJsonFile');

        $customers = $method->invoke($this->repository);

        $this->assertCount(3, $customers);
    }
}
