<?php
namespace Repositories;

include_once(__DIR__ . '/../config/dbconfig.php');

use Models\Product;

class ProductRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM product WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllProducts() {
        $query = "SELECT * FROM product";
        $result = mysqli_query($this->connection, $query);

        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }
        return $products;
    }

    public function create(Product $product) {
        $stmt = $this->connection->prepare("INSERT INTO product (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $product->name, $product->description, $product->price);
        $stmt->execute();
        $stmt->close();
    }

    public function update(Product $product) {
        $stmt = $this->connection->prepare("UPDATE product SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $product->name, $product->description, $product->price, $product->id);
        $stmt->execute();
        $stmt->close();
    }

    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM product WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
}
