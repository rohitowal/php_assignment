<?php
include_once(__DIR__ . '/../services/CurrencyProcessor.php');
include_once(__DIR__ . '/../services/LocationService.php');
include_once(__DIR__ . '/../config/dbconfig.php');

class CurrencyController {
    public static function handleProductPage() {
        //session_start();

        // If user selects country manually
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['country'])) {
            $_SESSION['country'] = $_POST['country'];
        }

        $country = $_SESSION['country'] ?? LocationService::detectCountryByIP();

        global $connection;
        $result = CurrencyProcessor::getConvertedProductList($connection, $country);

        // Store for cart and display
        $_SESSION['conversion_rate'] = $result['conversionRate'];
        $_SESSION['currency_info'] = $result['currencyInfo'];
        $_SESSION['country'] = $country;

        $products = $result['products'];
        $currencyCode = $result['currencyInfo']['code'];
        $currencySymbol = $result['currencyInfo']['symbol'];
        $countryCurrency = CurrencyService::$countryCurrency;

        include(__DIR__ . '/../views/ProductList.php');
    }
}