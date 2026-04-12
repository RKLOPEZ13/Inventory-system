<?php
// includes/db.php
// PDO Database Connection for AssetTrack
// Usage: require_once __DIR__ . '/db.php'; then use $pdo

define('DB_HOST', 'localhost');
define('DB_NAME', 'inventory_system');
define('DB_USER', 'root');        // Change to your MySQL user
define('DB_PASS', '');            // Change to your MySQL password
define('DB_CHARSET', 'utf8mb4');

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    DB_HOST, DB_NAME, DB_CHARSET
);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please check your configuration.',
        // Uncomment next line only during local development:
        // 'debug' => $e->getMessage(),
    ]));
}
