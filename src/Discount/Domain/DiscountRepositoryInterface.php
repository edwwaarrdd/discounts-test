<?php

namespace App\Discount\Domain;

interface DiscountRepositoryInterface
{
    /**
     * @return DiscountInterface[]
     */
    public function getAllActive(): array;
}
