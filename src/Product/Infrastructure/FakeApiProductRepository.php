<?php

namespace App\Product\Infrastructure;

use App\Money\Money;
use App\Product\Domain\Product;
use App\Product\Domain\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;

use function array_map;
use function file_get_contents;
use function json_decode;

class FakeApiProductRepository implements ProductRepositoryInterface
{
    public const string VALID_PRODUCT_ID = 'A101';
    public const string INVALID_PRODUCT_ID = 'UNKNOWN';
    /**
     * @var Product[]
     */
    private readonly array $products;

    public function __construct()
    {
        $this->products = $this->mapProductsFromJsonFile();
    }

    /**
     * @param ProductId $id
     */
    public function get(ProductId $id): Product
    {
        foreach ($this->products as $product) {
            if ($product->id->matches($id)) {
                return $product;
            }
        }

        throw new ProductNotFoundException($id);
    }

    /**
     * @return Product[]
     */
    private function mapProductsFromJsonFile(): array
    {
        $data = json_decode(
            (string)file_get_contents(__DIR__ . '/products.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return array_map(
            fn ($product) => new Product(
                id: new ProductId($product['id']),
                categoryId: new CategoryId($product['category']),
                description: $product['description'],
                price: Money::fromDecimal($product['price'], Money::EUR)
            ),
            $data
        );
    }
}
