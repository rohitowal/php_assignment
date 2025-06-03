<?php
include_once(__DIR__ . '/CurrencyService.php');

class LocationService {
    public static function detectCountryByIP() {
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION['country'])) {
            return $_SESSION['country'];
        }

        $apiKey = "d0d4154be35b476781a656874cc9c87c";
        $ip = $_SERVER['REMOTE_ADDR'];
       
        //$ip = "4.158.144.17";

        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip&fields=country_name";

        $json = @file_get_contents($url);
        $country = 'USA'; //Default country

        if ($json !== false) {
            $data = json_decode($json, true);
            $fullCountryName = $data['country_name'] ?? 'United States of America';
            $country = CurrencyService::$countryNameMap[$fullCountryName] ?? 'USA';

        }    
        $_SESSION['country'] = $country;
        return $country;
    }
}
?>