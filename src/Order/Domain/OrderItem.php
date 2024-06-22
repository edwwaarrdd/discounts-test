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

    public static function fromArray(array $data): self
    {
        return new self(
            productId: new ProductId($data['product-id']),
            quantity: $data['quantity'],
            unitPrice: Money::fromDecimal($data['unit-price'], Money::EUR),
            totalPrice: Money::fromDecimal($data['total'], Money::EUR),
        );
    }
}
