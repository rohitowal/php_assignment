<?php

namespace Services;

use Utils\Logger;

/**
 * Class CartService
 * 
 * Service responsible for managing shopping cart operations.
 * Features:
 * - Session-based cart storage
 * - Product addition with quantity management
 * - Automatic quantity updates for existing items
 * - Cart initialization and validation
 */
class CartService {
    
    /**
     * Adds a product to the user's cart stored in PHP session.
     * If the product already exists in the cart, its quantity will be increased.
     * If it's a new product, it will be added with the specified quantity.
     * 
     * @param array $product An associative array containing product details:
     *                      - id: unique product identifier
     *                      - name: product name
     *                      - price: product price
     * @param int $quantity Quantity of the product to add (must be positive)
     * 
     * @return void
     */
    public static function addProductToCart($product, $quantity) {
        // Ensure session is started for cart storage
        if (!isset($_SESSION)) 
            session_start(); 
        Logger::info("CartService::addProductToCart() called");

        // Initialize cart in session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            Logger::info("Initialized empty cart in session");
        }

        // Extract product details for cart entry
        $productId = $product['id'];
        $productName = $product['name'];
        $productPrice = $product['price'];

        // Update quantity if product exists, otherwise add new entry
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
            Logger::info("Increased quantity of product ID $productId ($productName) by $quantity. New quantity: " . $_SESSION['cart'][$productId]['quantity']);
        } else {
            // Add new product entry to cart
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'], 
                'quantity' => $quantity
            ];
            Logger::info("Added product to cart: ID $productId, Name: $productName, Price: $productPrice, Quantity: $quantity");
        }
    }
}





