<?php

namespace App\Product\Domain\ValueObjects;

final readonly class CategoryId
{
    public function __construct(public string $value)
    {
    }

    public function matches(CategoryId $categoryId): bool
    {
        return $this->value === $categoryId->value;
    }
}
