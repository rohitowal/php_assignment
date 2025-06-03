
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

include_once(__DIR__ . '/config/dbconfig.php'); 
include_once(__DIR__ . '/controllers/CurrencyController.php');
include_once(__DIR__ . '/controllers/CartController.php');
include_once(__DIR__ . '/controllers/OrderController.php');

// Handle POST requests first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? null;

    switch ($action) {
        case 'add_to_cart':
            CartController::addToCart($connection);
            break;
        case 'confirm_order':
            OrderController::confirmOrder($connection);
            break;
        default:
            CurrencyController::handleProductPage();
            break;
    }
} else {
    // GET requests
    $action = $_GET['action'] ?? null;

    switch ($action) {
        default:
            CurrencyController::handleProductPage();
            break;
    }
}
?>

