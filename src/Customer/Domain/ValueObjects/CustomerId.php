<?php

namespace App\Customer\Domain\ValueObjects;

final readonly class CustomerId
{
    public function __construct(public string $value)
    {
    }

    public function matches(self $customerId): bool
    {
        return $this->value === $customerId->value;
    }
}
