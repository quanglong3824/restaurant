<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Drop service_type column from menu_items
    $db->exec("ALTER TABLE `menu_items` DROP COLUMN `service_type`");
    echo "Successfully dropped 'service_type' column from 'menu_items'.\n";

} catch (PDOException $e) {
    if (str_contains($e->getMessage(), "check that column/key exists")) {
         echo "Column 'service_type' already dropped.\n";
    } else {
         echo "Database error: " . $e->getMessage() . "\n";
    }
}
?>