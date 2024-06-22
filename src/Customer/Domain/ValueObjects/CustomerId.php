<?php

namespace App\Customer\Domain\ValueObjects;

final readonly class CustomerId
{
    public function __construct(public string $id)
    {
    }

    public function matches(CustomerId $id): bool
    {
        return $this->id === $id->id;
    }
}
