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

    // Xóa chữ " (Room Service)" khỏi tên món ăn
    $stmt = $db->prepare("UPDATE `menu_items` SET `name` = REPLACE(`name`, ' (Room Service)', '') WHERE `name` LIKE '% (Room Service)%'");
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    echo "Đã xóa chữ '(Room Service)' thành công cho {$rowCount} món ăn trong cơ sở dữ liệu!\n";

} catch (PDOException $e) {
    echo "Lỗi kết nối CSDL: " . $e->getMessage() . "\n";
}
?>