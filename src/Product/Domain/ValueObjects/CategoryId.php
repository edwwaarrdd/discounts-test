<?php

namespace App\Product\Domain\ValueObjects;

final readonly class CategoryId
{
    public function __construct(public string $id)
    {
    }

    public function matches(CategoryId $id): bool
    {
        return $this->id === $id->id;
    }
}
