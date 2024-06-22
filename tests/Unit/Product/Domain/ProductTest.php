<?php

namespace Test\Unit\Product\Domain;

use App\Money\Money;
use App\Product\Domain\Product;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product(
            new ProductId('valid-id'),
            new CategoryId('valid-category-id'),
            'Product Description',
            Money::fromDecimal('100', Money::EUR)
        );
    }

    public function testProductCanBeCreatedWithValidParameters(): void
    {
        $this->assertTrue($this->product->id->matches(new ProductId('valid-id')));
        $this->assertTrue($this->product->categoryId->matches(new CategoryId('valid-category-id')));
        $this->assertEquals('Product Description', $this->product->description);
        $this->assertTrue($this->product->price->equals(Money::fromDecimal('100', Money::EUR)));
    }
}
