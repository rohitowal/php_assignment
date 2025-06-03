<?php
include_once(__DIR__ . '/../repositories/ProductRepository.php');
include_once(__DIR__ . '/CurrencyService.php');

class CurrencyProcessor {
    public static function getConvertedProductList($connection, $country) {
        $currencyInfo = CurrencyService::getCurrencyInfo($country);
        $conversionRate = CurrencyService::getExchangeRate('INR', $currencyInfo['code']);

        $repo = new ProductRepository($connection);
        $products = $repo->getAllProducts();

        foreach ($products as &$product) {
            $product['converted_price'] = $product['price'] * $conversionRate;
        }

        return [
            'products' => $products,
            'conversionRate' => $conversionRate,
            'currencyInfo' => $currencyInfo
        ];
    }
}