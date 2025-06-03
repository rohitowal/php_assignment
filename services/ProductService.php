<?php
include_once(__DIR__ . '/../repositories/ProductRepository.php');
include_once(__DIR__ . '/../models/Product.php');
include_once(__DIR__ . '/../config/dbconfig.php'); 

class ProductService {
    public static function getAll() {
        global $connection;
        $repo = new ProductRepository($connection);
        return $repo->getAllProducts(); 
    }

    public static function create($data) {
        global $connection;
        $product = new Product(null, $data['name'], $data['description'], floatval($data['price']));
        $repo = new ProductRepository($connection);
        $repo->create($product);
        return ["message" => "Product created"];
    }

    public static function update($data, $id) {
        global $connection;
        if (!$id) return ["error" => "Missing ID"];

        $product = new Product($id, $data['name'], $data['description'], floatval($data['price']));
        $repo = new ProductRepository($connection);
        $repo->update($product);
        return ["message" => "Product updated"];
    }

    public static function delete($id) {
        global $connection;
        if (!$id) return ["error" => "Missing ID"];

        $repo = new ProductRepository($connection);
        $deleted = $repo->delete($id);
        if ($deleted) {
            return ["message" => "Product deleted"];
        } else {
            return ["error" => "No product found with given ID"];
        }
    }
}

