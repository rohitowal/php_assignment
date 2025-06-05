<?php
namespace Models;

class OrderItem {
    public $id;
    public $orderId;
    public $productId;
    public $productName;
    public $quantity;
    public $price;

    public function __construct($id, $orderId, $productId, $productName, $quantity, $price) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->price = $price;
    }
}
