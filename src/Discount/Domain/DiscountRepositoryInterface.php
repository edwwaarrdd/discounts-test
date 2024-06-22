<?php

namespace App\Discount\Domain;

interface DiscountRepositoryInterface
{
    /**
     * @return array<DiscountInterface>
     */
    public function getAllActive(): array;
}
