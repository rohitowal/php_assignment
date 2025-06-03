<?php
include_once(__DIR__ . '/../repositories/OrderRepository.php');
include_once(__DIR__ . '/../models/Order.php');
include_once(__DIR__ . '/../models/OrderItem.php');

class OrderService {
    public static function createOrder($connection, $userId, $cart) {
        $orderRepo = new OrderRepository($connection);

        $order = new Order(null, $userId, date('Y-m-d H:i:s'));
        $orderId = $orderRepo->saveOrder($order);

        foreach ($cart as $productId => $item) {
            $orderItem = new OrderItem(
                null,
                $orderId,
                $productId,
                $item['name'],
                $item['quantity'],
                $item['price']
            );
            $orderRepo->saveOrderItem($orderItem);
        }

        return $orderId;
    }
}
