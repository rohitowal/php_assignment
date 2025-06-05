<?php

namespace Utils;

class Logger {
    private static $startTime;
    private static $appName;

    // Call this once on startup
    public static function init($appName) {
        self::$startTime = microtime(true);
        self::$appName = $appName;
    }

    private static function getElapsedTime() {
        return number_format((microtime(true) - self::$startTime), 4) . 's';
    }

    private static function log($level, $message) {
        $timestamp = date('Y-m-d h:i:s A');
        $elapsed = self::getElapsedTime();
        $appName = self::$appName ?? 'UnknownApp';

        $logMessage = "[$level] [$appName] $timestamp - $message | Elapsed: $elapsed" . PHP_EOL;
        error_log($logMessage, 3, __DIR__ . '/../logs/app.log');
    }

    public static function info($message) {
        self::log('INFO', $message);
    }

    public static function error($message) {
        self::log('ERROR', $message);
    }

    public static function debug($message) {
        self::log('DEBUG', $message);
    }
}
?>