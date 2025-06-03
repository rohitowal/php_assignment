<?php
include_once(__DIR__ . '/../services/ProductService.php');
include_once(__DIR__ . '/../services/CurrencyService.php');

class ProductController {

    public static function showProductList() {
        session_start();

        $products = ProductService::getAll();

        $conversionRate = $_SESSION['conversion_rate'] ?? 1;
        $currencyInfo = $_SESSION['currency_info'] ?? ['code' => 'USD', 'symbol' => '$'];
        $country = $_SESSION['country'] ?? 'USA';
        $countryCurrency = CurrencyService::$countryCurrency;
        $cart = $_SESSION['cart'] ?? [];

        include(__DIR__ . '/../views/ProductListView.php');
    }


    public static function getAll() {
        $products = ProductService::getAll();
        echo json_encode($products, JSON_PRETTY_PRINT);
    }

    public static function create($data) {
        $response = ProductService::create($data);
        echo json_encode($response);    
    }

    public static function update($data, $id) {
        $response = ProductService::update($data, $id);
        echo json_encode($response);
    }

    public static function delete($id) {
        $response = ProductService::delete($id);
        echo json_encode($response);
    }
}
?>