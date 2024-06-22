<?php

namespace App\Discount\Domain;

use App\Money\Money;

final readonly class TotalDiscount
{
    /**
     * @var GivenDiscount[]
     */
    public array $givenDiscounts;

    public function __construct(GivenDiscount ...$givenDiscounts)
    {
        $this->givenDiscounts = $givenDiscounts;
    }

    public function getTotal(): Money
    {
        $total = Money::zero(Money::EUR);

        foreach ($this->givenDiscounts as $givenDiscount) {
            $total = $total->add($givenDiscount->discountValue);
        }

        return $total;
    }
}
