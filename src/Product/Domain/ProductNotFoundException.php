<?php

namespace App\Product\Domain;

use App\Product\Domain\ValueObjects\ProductId;
use RuntimeException;

class ProductNotFoundException extends RuntimeException
{
    public function __construct(ProductId $id)
    {
        parent::__construct("Product with id {$id->id} not found");
    }
}
