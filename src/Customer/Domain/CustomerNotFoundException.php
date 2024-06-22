<?php

namespace App\Customer\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use RuntimeException;

class CustomerNotFoundException extends RuntimeException
{
    public function __construct(CustomerId $id)
    {
        parent::__construct("Customer with id {$id->id} not found");
    }
}
