<?php

namespace App\Order\Domain;

use App\Money\Money;
use App\Product\Domain\ValueObjects\ProductId;
use InvalidArgumentException;
use Throwable;

final readonly class OrderItem
{
    public function __construct(
        public ProductId $productId,
        public int $quantity,
        public Money $unitPrice,
        public Money $totalPrice
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        try {
            return new self(
                productId: new ProductId($data['product-id']),
                quantity: $data['quantity'],
                unitPrice: Money::fromDecimal($data['unit-price'], Money::EUR),
                totalPrice: Money::fromDecimal($data['total'], Money::EUR),
            );
        } catch (Throwable $e) {
            throw new InvalidArgumentException(message: 'Could not parse order item data', previous: $e);
        }
    }
}
