<?php

namespace App\Customer\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;

interface CustomerRepositoryInterface
{
    public function get(CustomerId $id): Customer;
}
