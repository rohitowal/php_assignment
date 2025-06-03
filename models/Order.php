<?php
class Order {
    public $id;
    public $userId;
    public $orderDate;

    public function __construct($id, $userId, $orderDate) {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
    }
}
