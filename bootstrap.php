<?php

require_once __DIR__ . '/vendor/autoload.php';
spl_autoload_unregister('activerecord_autoload');

session_start();

date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Utils\Logger;
use Repositories\ProductRepository;
use Services\CurrencyService;
use Services\CurrencyProcessor;
use Services\CartService;
use Controllers\CurrencyController;
use Controllers\CartController;

// Init logger & DB
require_once __DIR__ . '/utils/Logger.php';
Logger::init('Cart-Assignments');
require_once __DIR__ . '/config/dbconfig.php';

// Shared dependencies
$productRepo = new ProductRepository($connection);
$currencyService = new CurrencyService();
$currencyProcessor = new CurrencyProcessor($productRepo, $currencyService);
$currencyController = new CurrencyController($currencyProcessor, $currencyService);
$cartService = new CartService();
$cartController = new CartController($productRepo, $cartService);

// Make available globally
$GLOBALS['controllers'] = [
    'currency' => $currencyController,
    'cart' => $cartController,
    'connection' => $connection,
];
