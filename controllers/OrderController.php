<?php
include_once(__DIR__ . '/../services/OrderService.php');

class OrderController {
    public static function confirmOrder($connection) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            $userId = $_SESSION['user_id'] ?? null;

            if (!empty($cart)) {
                OrderService::createOrder($connection, $userId, $cart);
                unset($_SESSION['cart']);
                $_SESSION['order_confirmed'] = true;
                header("Location: index.php");
                exit;
            }
        }
    }
}