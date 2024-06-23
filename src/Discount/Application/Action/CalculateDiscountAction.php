<?php

namespace App\Discount\Application\Action;

use App\Discount\Application\Service\CalculateDiscountsService;
use App\Discount\Domain\TotalDiscount;
use App\Order\Application\Service\AugmentOrderService;
use App\Order\Domain\Order;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final readonly class CalculateDiscountAction
{
    public function __construct(
        private JsonRenderer $renderer,
        private AugmentOrderService $augmentOrderService,
        private CalculateDiscountsService $calculateDiscounts,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $order = Order::fromArray((array)$request->getParsedBody());
        } catch (Throwable $exception) {
            return $this->renderer
                ->json($response, ['error' => 'Invalid order structure'])
                ->withStatus(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY);
        }

        $augmentedOrder = $this->augmentOrderService->execute($order);

        $discounts = $this->calculateDiscounts->execute($augmentedOrder);

        return $this->renderer->json($response, $this->buildJsonResponse($discounts));
    }

    /**
     * @param TotalDiscount $totalDiscount
     *
     * @return array<string, mixed>
     */
    private function buildJsonResponse(TotalDiscount $totalDiscount): array
    {
        return [
            'totalDiscount' => $totalDiscount->getTotal()->toDecimal(),
            'discounts' => array_map(fn ($discount) => $discount->toArray(), $totalDiscount->givenDiscounts),
        ];
    }
}
