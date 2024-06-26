<?php

namespace App\Customer\Infrastructure;

use App\Customer\Domain\Customer;
use App\Customer\Domain\CustomerNotFoundException;
use App\Customer\Domain\CustomerRepositoryInterface;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use DateTimeImmutable;
use InvalidArgumentException;

use function array_map;
use function file_get_contents;
use function json_decode;

class FakeApiCustomerRepository implements CustomerRepositoryInterface
{
    public const string VALID_CUSTOMER_ID = '1';
    public const string INVALID_CUSTOMER_ID = 'UNKNOWN';
    /** @var Customer[] */
    private readonly array $customers;

    public function __construct()
    {
        $this->customers = $this->mapCustomersFromJsonFile();
    }

    public function get(CustomerId $id): Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->id->matches($id)) {
                return $customer;
            }
        }

        throw new CustomerNotFoundException($id);
    }

    /** @return Customer[] */
    private function mapCustomersFromJsonFile(): array
    {
        $data = json_decode(
            (string)file_get_contents(__DIR__ . '/customers.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return array_map(
            function ($customer) {
                $customerSince = DateTimeImmutable::createFromFormat('Y-m-d', $customer['since']);

                if ($customerSince === false) {
                    throw new InvalidArgumentException('Invalid since date format for customer ' . $customer['id']);
                }

                return new Customer(
                    id: new CustomerId($customer['id']),
                    name: $customer['name'],
                    revenue: Money::fromDecimal($customer['revenue'], Money::EUR),
                    customerSince: $customerSince,
                );
            },
            $data
        );
    }
}
