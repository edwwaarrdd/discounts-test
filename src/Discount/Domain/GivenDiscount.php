<?php

namespace App\Discount\Domain;

use App\Money\Money;

final readonly class GivenDiscount
{
    /**
     * @param  array<string, mixed>  $metadata
     * @param string $description
     * @param Money $discountValue
     */
    public function __construct(
        public string $description,
        public Money $discountValue,
        public array $metadata = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'discountValue' => $this->discountValue->toDecimal(),
            'metadata' => $this->metadata,
        ];
    }
}
