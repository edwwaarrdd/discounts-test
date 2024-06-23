<?php

namespace App\Product\Domain\ValueObjects;

final readonly class ProductId
{
    public function __construct(public string $value)
    {
    }

    public function matches(ProductId $productId): bool
    {
        return $this->value === $productId->value;
    }
}
