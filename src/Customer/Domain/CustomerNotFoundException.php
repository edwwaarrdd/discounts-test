<?php

namespace App\Customer\Domain;

use App\Customer\Domain\ValueObjects\CustomerId;
use App\Shared\Domain\DomainRecordNotFoundException;

class CustomerNotFoundException extends DomainRecordNotFoundException
{
    public function __construct(CustomerId $id)
    {
        parent::__construct("Customer with id $id->value not found");
    }
}
