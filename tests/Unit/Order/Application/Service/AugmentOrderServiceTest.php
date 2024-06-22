<?php

namespace Test\Unit\Order\Application\Service;

use App\Customer\Domain\Customer;
use App\Customer\Domain\CustomerNotFoundException;
use App\Customer\Domain\CustomerRepositoryInterface;
use App\Customer\Domain\ValueObjects\CustomerId;
use App\Money\Money;
use App\Order\Application\Service\AugmentOrderService;
use App\Order\Domain\AugmentedOrder;
use App\Order\Domain\AugmentedOrderItem;
use App\Order\Domain\Order;
use App\Order\Domain\OrderItem;
use App\Order\Domain\ValueObjects\OrderId;
use App\Product\Domain\Product;
use App\Product\Domain\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Product\Domain\ValueObjects\CategoryId;
use App\Product\Domain\ValueObjects\ProductId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AugmentOrderServiceTest extends TestCase
{
    public function testExecuteReturnsAugmentedOrderWithCorrectData(): void
    {
        $productRepository = $this->createMock(ProductRepositoryInterface::class);
        $customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $order = $this->order();
        $productRepository->method('get')->willReturn(
            new Product(
                id: new ProductId('product-id'),
                categoryId: new CategoryId('category-id'),
                description: 'Product',
                price: Money::fromDecimal('500', Money::EUR)
            )
        );
        $customerRepository->method('get')->willReturn(
            new Customer(
                id: new CustomerId('customer-id'),
                name: 'Customer',
                revenue: Money::fromDecimal('1000', Money::EUR),
                customerSince: new DateTimeImmutable('2021-01-01')
            )
        );

        $service = new AugmentOrderService($productRepository, $customerRepository);
        $result = $service->execute($order);

        $this->assertInstanceOf(AugmentedOrder::class, $result);
        $this->assertEquals($order->id, $result->id);
        $this->assertInstanceOf(Customer::class, $result->customer);
        $this->assertEquals($order->totalPrice, $result->totalPrice);
        $this->assertCount(1, $result->orderItems);
        $this->assertInstanceOf(AugmentedOrderItem::class, $result->orderItems[0]);
    }

    public function testExecuteThrowsExceptionWhenProductNotFound(): void
    {
        $productRepository = $this->createMock(ProductRepositoryInterface::class);
        $customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $order = $this->order();
        $productRepository->method('get')->willThrowException(new ProductNotFoundException(new ProductId('1')));

        $service = new AugmentOrderService($productRepository, $customerRepository);

        $this->expectException(ProductNotFoundException::class);
        $service->execute($order);
    }

    public function testExecuteThrowsExceptionWhenCustomerNotFound(): void
    {
        $productRepository = $this->createMock(ProductRepositoryInterface::class);
        $customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $order = $this->order();
        $productRepository->method('get')->willReturn(
            new Product(
                id: new ProductId('product-id'),
                categoryId: new CategoryId('category-id'),
                description: 'Product',
                price: Money::fromDecimal('500', Money::EUR)
            )
        );
        $customerRepository->method('get')->willThrowException(new CustomerNotFoundException(new CustomerId('1')));

        $service = new AugmentOrderService($productRepository, $customerRepository);

        $this->expectException(CustomerNotFoundException::class);
        $service->execute($order);
    }

    private function order(): Order
    {
        return new Order(
            new OrderId('order-id'),
            new CustomerId('customer-id'),
            Money::fromDecimal('1000', Money::EUR),
            new OrderItem(
                productId: new ProductId('product-id'),
                quantity: 2,
                unitPrice: Money::fromDecimal('500', Money::EUR),
                totalPrice: Money::fromDecimal('1000', Money::EUR),
            ),
        );
    }
}
