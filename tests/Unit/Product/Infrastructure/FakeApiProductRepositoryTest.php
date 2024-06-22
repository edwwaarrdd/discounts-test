<?php

namespace Test\Unit\Product\Infrastructure;

use App\Product\Domain\ProductNotFoundException;
use App\Product\Domain\ValueObjects\ProductId;
use App\Product\Infrastructure\FakeApiProductRepository;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FakeApiProductRepositoryTest extends TestCase
{
    private FakeApiProductRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new FakeApiProductRepository();
    }

    public function testGetProductWithValidId(): void
    {
        $productId = new ProductId($this->repository::VALID_PRODUCT_ID);

        $product = $this->repository->get($productId);

        $this->assertEquals($productId, $product->id);
    }

    public function testGetProductWithInvalidIdThrowsException(): void
    {
        $this->expectExceptionObject(
            new ProductNotFoundException(new ProductId($this->repository::INVALID_PRODUCT_ID))
        );

        $productId = new ProductId($this->repository::INVALID_PRODUCT_ID);
        $this->repository->get($productId);
    }

    public function testMapProductsFromJsonFileReturnsCorrectNumberOfProducts(): void
    {
        $reflection = new ReflectionClass(FakeApiProductRepository::class);
        $method = $reflection->getMethod('mapProductsFromJsonFile');

        $products = $method->invoke($this->repository);

        $this->assertCount(5, $products);
    }
}
