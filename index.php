<?php

require_once __DIR__ . '/vendor/autoload.php';
spl_autoload_unregister('activerecord_autoload');

date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Import classes
use Utils\Logger;
use Controllers\OrderController;
use Controllers\CurrencyController;
use Controllers\CartController;

use Repositories\OrderRepository;
use Repositories\ProductRepository;

use Services\OrderService;
use Services\CartService;
use Services\CurrencyService;
use Services\CurrencyProcessor;

// Init Logger & DB
include_once(__DIR__ . '/utils/Logger.php');
Logger::init('Cart-Assignments');
include_once(__DIR__ . '/config/dbconfig.php');

// Initialize shared dependencies
$productRepo = new ProductRepository($connection);
$currencyService = new CurrencyService();
$currencyProcessor = new CurrencyProcessor($productRepo, $currencyService);
$currencyController = new CurrencyController($currencyProcessor, $currencyService);
$cartService = new CartService();
$cartController = new CartController($productRepo, $cartService);

// Get clean path
$uri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

Logger::debug("Request Method: " . $_SERVER['REQUEST_METHOD']);
Logger::debug("Path: $uri");

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