<?php

namespace Models;

class Order {
    public $id;
    public $userId;
    public $orderDate;
    public $totalAmount;

    public function __construct($id, $userId, $orderDate,$totalAmount) {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
    }
}
