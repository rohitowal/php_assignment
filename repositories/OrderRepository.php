<?php

namespace Repositories;


use Models\OrderItem;
use Models\Order;

class OrderRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function saveOrder(Order $order) {
        $query = "INSERT INTO orders (user_id, order_date,total_amount) VALUES (?, ?,?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isd", $order->userId, $order->orderDate,$order->totalAmount);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        return $orderId;
    }

    public function saveOrderItem(OrderItem $item) {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iisis", $item->orderId, $item->productId, $item->productName, $item->quantity, $item->price);
        $stmt->execute();
        $stmt->close();
    }
}
