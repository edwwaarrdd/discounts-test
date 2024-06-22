<?php

namespace App\Order\Domain\ValueObjects;

final readonly class OrderId
{
    public function __construct(public string $id)
    {
    }

    public function matches(self $id): bool
    {
        return $this->id === $id->id;
    }
}
