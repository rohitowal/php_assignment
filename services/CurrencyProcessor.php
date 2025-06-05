<?php

namespace Services;

use Repositories\ProductRepository;
use Services\CurrencyService;


/**
 * Class CurrencyProcessor
 * 
 * Responsible for handling product price conversion based on country and currency.
 */
class CurrencyProcessor {
   
    /**
     * Fetches the product list and converts product prices based on country.
     *
     * @param mysqli $connection database connection object.
     * @param string $country The country name or code used to determine the target currency.
     *
     * @return array An associative array containing:
     *               - 'products': the list of products with converted prices,
     *               - 'conversionRate': the currency conversion rate,
     *               - 'currencyInfo': the currency metadata (code and symbol).
     */
    public static function getConvertedProductList($connection, $country) {

        //get currency info based on country
        $currencyInfo = CurrencyService::getCurrencyInfo($country);

        // Get the conversion rate from base currency (INR) to the target currency
        $conversionRate = CurrencyService::getExchangeRate('INR', $currencyInfo['code']);

        $repo = new ProductRepository($connection);
        $products = $repo->getAllProducts();

        // Loop through each product and calculate the converted price
        foreach ($products as &$product) {
            $product['converted_price'] = $product['price'] * $conversionRate;
        }

        // Return the final product list with converted prices and currency details.
        return [
            'products' => $products,
            'conversionRate' => $conversionRate,
            'currencyInfo' => $currencyInfo
        ];
    }
}