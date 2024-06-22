<?php

namespace App\Product\Domain;

use App\Money\Money;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;

final readonly class Product
{
    public function __construct(
        public ProductId $id,
        public CategoryId $categoryId,
        public string $description,
        public Money $price
    ) {
    }
}
