<?php

namespace App\Product\Domain\ValueObjects;

final readonly class ProductId
{
    public function __construct(public string $id)
    {
    }

    public function matches(ProductId $id): bool
    {
        return $this->id === $id->id;
    }
}
