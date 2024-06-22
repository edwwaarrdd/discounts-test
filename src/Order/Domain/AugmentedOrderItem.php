<?php

namespace App\Order\Domain;

use App\Money\Money;
use App\Product\Domain\Product;
use App\Product\Domain\ValueObjects\ProductId;

final readonly class AugmentedOrderItem
{
    public function __construct(
        public Product $product,
        public int $quantity,
        public Money $unitPrice,
        public Money $totalPrice
    ) {
    }
}
