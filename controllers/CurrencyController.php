<?php

namespace Controllers;

use Services\CurrencyProcessor;
use Services\LocationService;
use Utils\Logger;
use Services\CurrencyService;

include_once(__DIR__ . '/../config/dbconfig.php');


/**
 * class CurrencyController
 * 
 * Handel currency conversion logic according to the country
 */
class CurrencyController {

    /**
     * Responsible for currency conversion using country.
     * 
     * @return void 
     */
    public static function handleProductPage() {
    Logger::info("CurrencyController::handleProductPage() called");

    if (!isset($_SESSION)) session_start();

    $country = $_SESSION['country'] ?? null; // Preserve already set country
    $manualOverride = false;

    // If manual selection via POST, override
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['country'])) {
        $country = $_POST['country'];
        $manualOverride = true; // set manual override flag
        Logger::info("User selected country manually: $country");
    }

    // Only detect via IP if country is not set yet and no manual override
    if (!$country && !$manualOverride) {
        try {
            $country = LocationService::detectCountryByIP();
            Logger::info("Country detected by IP: $country");
        } catch (\Exception $e) {
            Logger::error("IP detection failed: " . $e->getMessage());
            $country = 'USA'; // fallback
        }
    }

    if (!$country) {
        $country = 'USA';
        Logger::info("Fallback to default country: $country");
    }

    // Always store the resolved country in session
    $_SESSION['country'] = $country;

    Logger::info("Final resolved country: $country");

    global $connection;

    try {
        $result = CurrencyProcessor::getConvertedProductList($connection, $country);
        Logger::info("Currency conversion successful for country: " . $country);
    } catch (\Exception $e) {
        Logger::error("Error during currency conversion: " . $e->getMessage());
        throw $e;
    }

    $_SESSION['conversion_rate'] = $result['conversionRate'];
    $_SESSION['currency_info'] = $result['currencyInfo'];

    $products = $result['products'];
    $currencyCode = $result['currencyInfo']['code'];
    $currencySymbol = $result['currencyInfo']['symbol'];
    $countryCurrency = CurrencyService::$countryCurrency;

    // Detect if the client expects JSON
$acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
$wantsJson = stripos($acceptHeader, 'application/json') !== false;
if ($wantsJson) {
    header('Content-Type: application/json');

    echo json_encode([
        'status' => 'success',
        'currency' => $result['currencyInfo'],
        'country' => $country,
        'products' => array_map(function ($p) use ($result) {
            $p['currency_code'] = $result['currencyInfo']['code'];
            $p['currency_symbol'] = $result['currencyInfo']['symbol'];
            return $p;
        }, $products)
    ], JSON_PRETTY_PRINT);
    exit;
}

    Logger::info("Loading ProductList view with " . count($products) . " products.");
    include(__DIR__ . '/../views/ProductList.php');
}

}
?>