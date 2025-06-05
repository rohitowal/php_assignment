

<?php

require_once __DIR__ . '/vendor/autoload.php';
spl_autoload_unregister('activerecord_autoload');

// Set timezone for consistent timestamps
date_default_timezone_set('Asia/Kolkata');  

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize session
session_start();

use Utils\Logger;
use Controllers\OrderController;
use Controllers\CurrencyController;
use Controllers\CartController;
// Include required dependencies
include_once(__DIR__ . '/utils/Logger.php'); 
Logger::init('Cart-Assignments');                   

include_once(__DIR__ . '/config/dbconfig.php'); 

// Log request method for debugging
Logger::debug('Request Method: ' . $_SERVER['REQUEST_METHOD']);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? null;
    Logger::debug("Handling POST action: " . ($action ?? 'none'));

    // Route POST requests to appropriate controllers
    switch ($action) {
        case 'add_to_cart':
            Logger::info("Action: add_to_cart");
            CartController::addToCart($connection);
            break;
        case 'confirm_order':
            Logger::info("Action: confirm_order");
            OrderController::confirmOrder($connection);
            break;
        default:
            Logger::debug("Unknown POST action, loading product page");
            CurrencyController::handleProductPage();
            break;
    }
} else {
    // Handle GET requests
    $action = $_GET['action'] ?? null;
    Logger::debug("Handling GET action: " . ($action ?? 'default'));

    // Route GET requests (currently only default product page)
    switch ($action) {
        default:
            CurrencyController::handleProductPage();
            break;
    }
}
?>
