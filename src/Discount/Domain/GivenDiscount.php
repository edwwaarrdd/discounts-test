<?php

namespace App\Discount\Domain;

use App\Money\Money;

final readonly class GivenDiscount
{
    public function __construct(
        public string $description,
        public Money $discountValue,
        public array $metadata = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'discountValue' => $this->discountValue->toDecimal(),
            'metadata' => $this->metadata,
        ];
    }
}
