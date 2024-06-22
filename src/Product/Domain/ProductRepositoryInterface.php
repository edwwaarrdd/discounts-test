<?php

namespace App\Product\Domain;

use App\Product\Domain\ValueObjects\ProductId;

interface ProductRepositoryInterface
{
    public function get(ProductId $id): Product;
}
