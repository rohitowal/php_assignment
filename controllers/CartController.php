<?php
include_once(__DIR__ . '/../repositories/ProductRepository.php');
include_once(__DIR__ . '/../services/CartService.php');

class CartController {
    public static function addToCart($connection) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];

            $repo = new ProductRepository($connection);
            $product = $repo->findById($productId);

            if ($product) {
                CartService::addProductToCart($product, $quantity);
            }

            header("Location: index.php");
            exit;
        }
    }
}

?>