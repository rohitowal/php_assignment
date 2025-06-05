<?php

namespace Services;

use Services\CurrencyService;
use Utils\Logger;

require_once __DIR__ . '/../utils/Env.php';
loadEnv(__DIR__ . '/../.env');


/**
 * Class LocationService
 * 
 * Responsible for detecting the user's country based on their IP address
 * using the ipgeolocation.io API.
 */
class LocationService {

    //used to detect the country using IP address
    public static function detectCountryByIP() {
        if (!isset($_SESSION)) session_start();

        $apiKey = $_ENV['LOCATION_API_KEY'];
        //$ip = $_SERVER['REMOTE_ADDR'];

        $ip = "81.2.69.160";
        Logger::info("Attempting IP geolocation for IP: $ip");
        Logger::info("Server info : " . json_encode($_SERVER));
        //fetching the country using IP
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip&fields=country_name";

        //converting the response into json
        $json = @file_get_contents($url);
        $country = 'USA'; //Default country
        Logger::info("API RESPONSE : $json");

        if ($json !== false) {
            //decode JSON response in associative array
            $data = json_decode($json, true);
            //extract full country name 
            $fullCountryName = $data['country_name'] ?? 'United States of America';
            Logger::info("Geolocation API returned country: $fullCountryName");

            $country = CurrencyService::$countryNameMap[$fullCountryName] ?? 'USA';

        }else{
            Logger::error("Failed to fetch geolocation data from API.");
        }

        Logger::info("Resolved country: $country");
        //store detected country in session for further use
        $_SESSION['country'] = $country;
        //return the final resolved country.
        return $country;
    }
}
?>