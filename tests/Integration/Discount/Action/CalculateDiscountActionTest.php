<?php

namespace Test\Integration\Discount\Action;

use App\Customer\Domain\CustomerNotFoundException;
use App\Customer\Domain\CustomerRepositoryInterface;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Customer\Infrastructure\FakeApiCustomerRepository;
use App\Discount\Domain\BulkCategoryDiscountOnCheapestItem;
use App\Discount\Domain\BuyXgetXFreeInCategoryDiscount;
use App\Discount\Domain\DiscountRepositoryInterface;
use App\Discount\Domain\LoyaltyDiscount;
use App\Money\Money;
use App\Product\Domain\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;
use App\Product\Infrastructure\FakeApiProductRepository;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Traits\AppTestTrait;

use function json_decode;
use function json_encode;

class CalculateDiscountActionTest extends TestCase
{
    use AppTestTrait;

    public function setUp(): void
    {
        $this->setUpApp();
        $this->setContainerValue(DiscountRepositoryInterface::class, $this->createDiscountRepository());
        $this->setContainerValue(ProductRepositoryInterface::class, new FakeApiProductRepository());
        $this->setContainerValue(CustomerRepositoryInterface::class, new FakeApiCustomerRepository());
    }

    private const string URL = '/discounts/calculate';

    #[DataProvider('calculateDiscountActionDataProvider')]
    public function testCalculateDiscountAction(string $requestJson, string $responseJson): void
    {
        $request = $this->createJsonRequest('POST', self::URL, $this->getRequestFixture($requestJson));

        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertResponseContains($this->getResponseFixture($responseJson), $response);
    }

    public function testItCanHandleUnknownCustomer(): void
    {
        $data = [
            'id' => '3',
            'customer-id' => '4',
            'items' => [],
            'total' => '69.00',
        ];
        $request = $this->createJsonRequest('POST', self::URL, $data);

        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
        $this->assertResponseContains((new CustomerNotFoundException(new CustomerId('4')))->getMessage(), $response);
    }

    public function testItCanHandleUnknownProductId(): void
    {
        $data = [
            'id' => '3',
            'customer-id' => '2',
            'items' => [
                [
                    'product-id' => 'UNKNOWN',
                    'quantity' => 1,
                    'unit-price' => '69.00',
                    'total' => '69.00',
                ],
            ],
            'total' => '69.00',
        ];
        $request = $this->createJsonRequest('POST', self::URL, $data);

        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
        $this->assertResponseContains(
            (new ProductNotFoundException(new ProductId('UNKNOWN')))->getMessage(),
            $response
        );
    }

    public function testItCanHandleMalformedData()
    {
        $data = [
            'foo' => 'bar',
        ];
        $request = $this->createJsonRequest('POST', self::URL, $data);

        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertResponseContains(
            'Could not parse order data',
            $response
        );
    }

    private function getRequestFixture(string $requestJson): array
    {
        return json_decode($this->getFixture($requestJson), true);
    }

    private function getResponseFixture(string $responseJson): string
    {
        return json_encode(json_decode($this->getFixture($responseJson), true));
    }

    private function getFixture(string $name): string
    {
        return file_get_contents(__DIR__ . "/fixtures/$name");
    }

    public static function calculateDiscountActionDataProvider(): array
    {
        return [
            'order 1' => [
                'requestJson' => 'order1.json',
                'responseJson' => 'response1.json',
            ],
            'order 2' => [
                'requestJson' => 'order2.json',
                'responseJson' => 'response2.json',
            ],
            'order 3' => [
                'requestJson' => 'order3.json',
                'responseJson' => 'response3.json',
            ],
            'order with multiple types of discounts' => [
                'requestJson' => 'order4.json',
                'responseJson' => 'response4.json',
            ],
        ];
    }

    private function createDiscountRepository(): DiscountRepositoryInterface
    {
        return new class () implements DiscountRepositoryInterface {
            public function getAllActive(): array
            {
                return [
                    new BulkCategoryDiscountOnCheapestItem(
                        categoryId: new CategoryId('1'),
                        minimumQuantity: 2,
                        percentage: 20
                    ),
                    new BuyXgetXFreeInCategoryDiscount(
                        categoryId: new CategoryId('2'),
                        buyQuantity: 5,
                        freeQuantity: 1
                    ),
                    new LoyaltyDiscount(
                        minimumRevenue: Money::fromDecimal('1000', Money::EUR),
                        percentage: 10
                    ),
                ];
            }
        };
    }
}
