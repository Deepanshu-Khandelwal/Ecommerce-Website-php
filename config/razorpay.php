<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env if not already loaded
if (!getenv('RAZORPAY_KEY_ID')) {
    if (!function_exists('loadEnv')) {
        function loadEnv($path)
        {
            if (!file_exists($path)) {
                return;
            }
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') === false) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (preg_match('/^"(.*)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                    $value = $matches[1];
                }
                
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv("{$name}={$value}");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
    loadEnv(dirname(__DIR__) . '/.env');
}

use Razorpay\Api\Api;

if (!defined('RAZORPAY_KEY_ID')) {
    $db_key = !empty($GLOBALS['settings']['razorpay_key_id']) ? $GLOBALS['settings']['razorpay_key_id'] : (getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_RbwFpsLIcK8Ko9');
    define('RAZORPAY_KEY_ID', $db_key);
}
if (!defined('RAZORPAY_KEY_SECRET')) {
    $db_secret = !empty($GLOBALS['settings']['razorpay_key_secret']) ? $GLOBALS['settings']['razorpay_key_secret'] : (getenv('RAZORPAY_KEY_SECRET') ?: '3XAq2n784WMvxTJKcl9wA9lz');
    define('RAZORPAY_KEY_SECRET', $db_secret);
}

function razorpayApi(): Api {
    return new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
}
?>
