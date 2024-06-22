<?php

namespace App\Discount\Domain;

use App\Money\Money;

class GivenDiscount
{
    public function __construct(
        public string $description,
        public Money $discountValue,
        public array $metadata = []
    ) {
    }
}
