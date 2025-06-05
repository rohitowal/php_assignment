<?php

namespace Services;

use Repositories\OrderRepository;
use Models\Order;
use Models\OrderItem;

/**
 * Class OrderService
 * 
 * Service layer for handling order-related business logic.
 * Manages the creation and processing of orders and their items.
 */
class OrderService {
    /**
     * Creates a new order with items from the shopping cart
     * 
     * This method:
     * 1. Creates a new order record
     * 2. Processes each cart item into order items
     * 3. Saves all order items to the database
     * 
     * @param mysqli $connection Database connection object
     * @param int|null $userId ID of the user placing the order (can be null for guest orders)
     * @param array $cart Shopping cart data containing items to be ordered
     * 
     * @return int The ID of the newly created order
     */
    public static function createOrder($connection, $userId, $cart) {
        $orderRepo = new OrderRepository($connection);

        //calculate total amount
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        // Create main order record
        $order = new Order(null, $userId, date('Y-m-d H:i:s'),$totalAmount);
        $orderId = $orderRepo->saveOrder($order);

        // Process each cart item into order items
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

        return $orderItem;
    }
}
