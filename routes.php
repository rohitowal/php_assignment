<?php

use Controllers\OrderController;
use Repositories\OrderRepository;
use Services\OrderService;
use Utils\Logger;

$uri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

Logger::debug("Request Method: " . $_SERVER['REQUEST_METHOD']);
Logger::debug("Path: $uri");

$currencyController = $GLOBALS['controllers']['currency'];
$cartController = $GLOBALS['controllers']['cart'];
$connection = $GLOBALS['controllers']['connection'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($uri) {
            case 'add-to-cart':
                Logger::info("Route: /add-to-cart");
                $cartController->addToCart();
                break;

            case 'confirm-order':
                Logger::info("Route: /confirm-order");
                $orderRepo = new OrderRepository($connection);
                $orderService = new OrderService($orderRepo);
                $orderController = new OrderController($orderService);
                $orderController->confirmOrder($connection);
                break;

            default:
                Logger::info("Route: /products");
                $currencyController->handleProductPage();
                break;
        }
        break;

    case 'GET':
    default:
        Logger::info("Route: /products");
        $currencyController->handleProductPage();
        break;
}
