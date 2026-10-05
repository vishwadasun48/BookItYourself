<?php
// BookItYourself payment configuration
// XAMPP defaults. Change these only if your MySQL settings are different.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookityourself_db');

// PayHere Sandbox credentials - replace with your own values.
define('PAYHERE_MERCHANT_ID', '1238505');
define('PAYHERE_MERCHANT_SECRET', 'MzQwODcyMTg0NjI4NTUyMzMzNTIzMTYyOTg3OTI5MTY5OTgwMzU0MQ==');
define('PAYHERE_CHECKOUT_URL', 'https://sandbox.payhere.lk/pay/checkout');

// IMPORTANT: For real PayHere notifications this must be a PUBLIC HTTPS URL.
// Example: https://yourdomain.com/payment_notify.php
define('SITE_URL', 'http://localhost/bookityourself');

function db_connection(): mysqli {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        die('Database connection failed: ' . $db->connect_error);
    }
    $db->set_charset('utf8mb4');
    return $db;
}
