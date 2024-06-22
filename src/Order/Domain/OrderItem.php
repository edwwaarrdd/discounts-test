<?php

namespace App\Order\Domain;

use App\Money\Money;
use App\Product\Domain\ValueObjects\ProductId;

final readonly class OrderItem
{
    public function __construct(
        public ProductId $productId,
        public int $quantity,
        public Money $unitPrice,
        public Money $totalPrice
    ) {
    }
}
