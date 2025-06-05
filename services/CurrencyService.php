<?php

namespace Services;

require_once __DIR__ . '/../utils/Env.php';
loadEnv(__DIR__ . '/../.env');

/**
 * Class CurrencyService
 * 
 * Handles currency-related utilities including mapping countries to currencies
 * and fetching real-time exchange rates using Fixer.io API.
 */
class CurrencyService {
    /**
     * A mapping of simplified country names to their corresponding currency codes and symbols.
     */
    public static $countryCurrency = [
        'India' => ['code' => 'INR', 'symbol' => '₹'],
        'USA' => ['code' => 'USD', 'symbol' => '$'],
        'UK' => ['code' => 'GBP', 'symbol' => '£'],
        'Germany' => ['code' => 'EUR', 'symbol' => '€'],
        'Australia' => ['code' => 'AUD', 'symbol' => 'A$'],
    ];

    /**
     * Maps full country names to simplified keys used in $countryCurrency.
     * This helps normalize input from various sources 
     */
    public static $countryNameMap = [
        'United States of America' => 'USA',
        'United Kingdom' => 'UK',
        'India' => 'India',
        'Germany' => 'Germany',
        'Australia' => 'Australia',
        // Map other European countries to Germany (EUR)
        'Austria' => 'Germany',
        'France' => 'Germany',
        'Spain' => 'Germany',
        'Italy' => 'Germany',
        'Netherlands' => 'Germany',
        'Belgium' => 'Germany',
    ];


     /**
     * Retrieves the currency information for the given country.
     *
     * @param string $country The full or normalized name of the country.
     * @return array An array with keys 'code' and 'symbol'.
     */
    public static function getCurrencyInfo($country) {
        // Map full country name to short key
        $key = self::$countryNameMap[$country] ?? $country;
        return self::$countryCurrency[$key]  ?? self::$countryCurrency['USA'];
    }


    /**
     * Fetches the exchange rate between two currencies using Fixer.io API.
     *
     * @param string $from The base currency code (e.g., 'INR').
     * @param string $to The target currency code (e.g., 'USD').
     * @return float The conversion rate from the base to the target currency. Defaults to 1 on failure.
     */
    public static function getExchangeRate($from = 'INR', $to = 'USD') {
       
        $apiKey = $_ENV['CURRENCY_EXCHANGE_API_KEY'];
        //fetches current exchange rate
        $url = "http://data.fixer.io/api/latest?access_key=$apiKey&format=1";
        $response = file_get_contents($url);
        
        if (!$response) 
            return 1;

        $data = json_decode($response, true);

        if (!isset($data['rates'][$from], $data['rates'][$to])) 
            return 1;

        return $data['rates'][$to] / $data['rates'][$from];
    }
}
?>