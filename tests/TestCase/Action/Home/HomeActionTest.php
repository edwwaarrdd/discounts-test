<?php

namespace Test\TestCase\Action\Home;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Test\Traits\AppTestTrait;

class HomeActionTest extends TestCase
{
    use AppTestTrait;

    public function testAction(): void
    {
        $request = $this->createRequest('GET', '/');
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertResponseContains('Welcome!', $response);
    }

    public function testPageNotFound(): void
    {
        $request = $this->createRequest('GET', '/nada');
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
