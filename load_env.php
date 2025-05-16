<?php
if (!defined('ENV_LOADED')) {
    define('ENV_LOADED', true);
    if (!defined('BASE_PATH')) {
        define('BASE_PATH', __DIR__);
    }

    $envPath = __DIR__ . '/.env'; // Assumes .env is in the same directory as this script

    if (is_readable($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) { // Skip comments
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove quotes from the value if present
            if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
                $value = substr($value, 1, -1);
            }

            if (!isset($_ENV[$name])) {
                $_ENV[$name] = $value;
            }
            if (!isset($_SERVER[$name])) {
                $_SERVER[$name] = $value;
            }
        }
    } else {
        // Fallback if .env is not found, you might want to log this or handle it differently
        // For now, we'll set a default empty PATH if .env is not readable
        if (!isset($_ENV['PATH'])) {
            $_ENV['PATH'] = '';
        }
        if (!isset($_SERVER['PATH'])) {
            $_SERVER['PATH'] = '';
        }
    }
}
?> 