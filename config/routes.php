<?php

// Define app routes

use App\Action\Home\HomeAction;
use App\Discount\Application\Action\CalculateDiscountAction;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class)->setName('home');
    $app->post('/discounts/calculate', CalculateDiscountAction::class)->setName('discount.calculate');
};
