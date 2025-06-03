<?php
class CurrencyService {
    public static $countryCurrency = [
        'India' => ['code' => 'INR', 'symbol' => '₹'],
        'USA' => ['code' => 'USD', 'symbol' => '$'],
        'UK' => ['code' => 'GBP', 'symbol' => '£'],
        'Germany' => ['code' => 'EUR', 'symbol' => '€'],
        'Australia' => ['code' => 'AUD', 'symbol' => 'A$'],
    ];

    public static $countryNameMap = [
        'United States of America' => 'USA',
        'United Kingdom' => 'UK',
        'India' => 'India',
        'Germany' => 'Germany',
        'Australia' => 'Australia',
        'Austria' => 'Germany',
        'France' => 'Germany',
        'Spain' => 'Germany',
        'Italy' => 'Germany',
        'Netherlands' => 'Germany',
        'Belgium' => 'Germany',
    ];

    public static function getCurrencyInfo($country) {
        // Map full country name to short key
        $key = self::$countryNameMap[$country] ?? $country;
        return self::$countryCurrency[$key]  ?? self::$countryCurrency['USA'];
    }

    public static function getExchangeRate($from = 'INR', $to = 'USD') {
        $apiKey = '52d7535265ef48b79d1dc990a155b623';
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