<?php

namespace App\Product\Domain;

use App\Product\Domain\ValueObjects\ProductId;
use App\Shared\Domain\DomainRecordNotFoundException;

class ProductNotFoundException extends DomainRecordNotFoundException
{
    public function __construct(ProductId $id)
    {
        parent::__construct("Product with id $id->value not found");
    }
}
