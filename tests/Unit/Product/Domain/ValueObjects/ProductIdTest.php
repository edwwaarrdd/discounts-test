<?php

namespace Test\Unit\Product\Domain\ValueObjects;

use App\Product\Domain\ValueObjects\ProductId;
use PHPUnit\Framework\TestCase;

class ProductIdTest extends TestCase
{
    private ProductId $productId;

    protected function setUp(): void
    {
        $this->productId = new ProductId('valid-id');
    }

    public function testProductIdCanBeCreatedWithValidId(): void
    {
        $this->assertEquals('valid-id', $this->productId->value);
    }

    public function testProductIdMatchesReturnsTrueWhenIdsMatch(): void
    {
        $matchingProductId = new ProductId('valid-id');

        $this->assertTrue($this->productId->matches($matchingProductId));
    }

    public function testProductIdMatchesReturnsFalseWhenIdsDoNotMatch(): void
    {
        $nonMatchingProductId = new ProductId('non-matching-id');

        $this->assertFalse($this->productId->matches($nonMatchingProductId));
    }
}
