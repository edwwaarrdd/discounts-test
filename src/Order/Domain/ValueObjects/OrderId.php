<?php

namespace App\Order\Domain\ValueObjects;

final readonly class OrderId
{
    public function __construct(public string $value)
    {
    }

    public function matches(self $orderId): bool
    {
        return $this->value === $orderId->value;
    }
}
