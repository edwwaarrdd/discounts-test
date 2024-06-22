<?php

namespace App\Customer\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use DateTimeImmutable;

final readonly class Customer
{
    public function __construct(
        public CustomerId $id,
        public string $name,
        public Money $revenue,
        public DateTimeImmutable $customerSince,
    ) {
    }
}
