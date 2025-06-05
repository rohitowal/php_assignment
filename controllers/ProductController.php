<?php

namespace Controllers;

use Services\ProductService;
use Services\CurrencyService;
use Utils\Logger;
/**
 * Class ProductController
 * 
 * Handles all product-related operations including:
 * - Displaying product listings
 * - CRUD operations for products
 * - Currency conversion for product prices
 * - Session management for product display
 */
class ProductController {

    /**
     * Display the product list view with currency conversion
     * 
     * This method:
     * - Fetches all products
     * - Applies currency conversion based on user's country
     * - Loads session data for cart and currency
     * - Renders the product list view
     * 
     * @return void
     */
    public static function showProductList() {
        Logger::info("ProductController::showProductList() called");
        session_start();
        
        // Fetch all products from the service
        $products = ProductService::getAll();
        Logger::info("Fetched products: " . json_encode($products));

        // Get session data for currency conversion and cart
        $conversionRate = $_SESSION['conversion_rate'] ?? 1;
        $currencyInfo = $_SESSION['currency_info'] ?? ['code' => 'USD', 'symbol' => '$'];
        $country = $_SESSION['country'] ?? 'USA';
        $countryCurrency = CurrencyService::$countryCurrency;
        $cart = $_SESSION['cart'] ?? [];

        Logger::info("Session data - Conversion Rate: $conversionRate, Currency Info: " . json_encode($currencyInfo) . ", Country: $country, Cart: " . json_encode($cart));        

        include(__DIR__ . '/../views/ProductListView.php');
        Logger::info("ProductListView.php included");
    }

    /**
     * Retrieve and return all products as JSON
     * 
     * @return void Outputs JSON encoded product list
     */
    public static function getAll() {
        Logger::info("ProductController::getAll() called");

        $products = ProductService::getAll();
        Logger::info("Products fetched: " . json_encode($products));

        echo json_encode($products, JSON_PRETTY_PRINT);
    }

    /**
     * Create a new product
     * 
     * @param array $data Product data including name, description, and price
     * @return void Outputs JSON encoded response
     */
    public static function create($data) {
        Logger::info("ProductController::create() called with data: " . json_encode($data));

        $response = ProductService::create($data);
        Logger::info("Product created with response: " . json_encode($response));
        
        echo json_encode($response);    
    }

    /**
     * Update an existing product
     * 
     * @param array $data Updated product data
     * @param int $id Product ID to update
     * @return void Outputs JSON encoded response
     */
    public static function update($data, $id) {
        Logger::info("ProductController::update() called for ID: $id with data: " . json_encode($data));
        $response = ProductService::update($data, $id);
        echo json_encode($response);
    }

    /**
     * Delete a product
     * 
     * @param int $id Product ID to delete
     * @return void Outputs JSON encoded response
     */
    public static function delete($id) {
        Logger::info("ProductController::delete() called for ID: $id");
        $response = ProductService::delete($id);
        echo json_encode($response);
    }
}
?>