<?php

namespace Controllers;

use Repositories\ProductRepository;
use Services\CartService;
use Utils\Logger;

/**
 * class CartController
 * 
 * used to handell cart-related actions such as adding product to the cart. 
 */
class CartController {
    /**
     * Handel add product to the cart
     * 
     * @param mysqli $connection Database connection object.
     * @return void
     */
    public static function addToCart($connection) {
        Logger::info("CartController::addToCart() called");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Logger::info("Request method is POST");

         // Sanitize inputs
            $productId = (int)($_POST['product_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            Logger::info("Received product_id: $productId, quantity: $quantity");

            $repo = new ProductRepository($connection);
            $product = $repo->findById($productId);

            // Prepare response data
            $response = [];

            if ($product) {
                Logger::info("Product found: " . $product['name']);
                CartService::addProductToCart($product, $quantity);
                Logger::info("Product added to cart: ID $productId, Quantity $quantity");

                $response['status'] = 'success';
                $response['message'] = 'Product added to cart';
                $response['product'] = $product;
                $response['quantity'] = $quantity;
            } else {
                Logger::error("Product not found for ID: $productId");
                $response['status'] = 'error';
                $response['message'] = "Product not found for ID: $productId";
            }

            // Detect if request expects JSON (API call)
            $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
            $isApiCall = strpos($acceptHeader, 'application/json') !== false;

            if ($isApiCall) {
                // Return JSON response for API/Postman
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // For frontend, redirect back to product page
                header("Location: index.php");
                exit;
            }
        } else {
            Logger::error("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
        }
    }

}

?>