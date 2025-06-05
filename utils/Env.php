<?php

/**
 * Loads environment variables from a .env file into $_ENV
 *
 * @param string $path Full path to the .env file
 * @return void
 */
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue; // skip comments

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"");

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}
