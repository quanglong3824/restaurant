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

    // SQL file with the 42 missing items from the old backup
    $sqlData = file_get_contents(__DIR__ . '/missing_items.sql');
    if (!$sqlData) {
        die("Không tìm thấy file missing_items.sql.\n");
    }

    $lines = explode("\n", trim($sqlData));
    $inserted = 0;
    
    // Bảng menu_items trong backup cũ có 18 cột (cột thứ 11 là service_type).
    // Trong bảng hiện tại, cột service_type đã bị xóa.
    // Dùng regex để loại bỏ giá trị của cột service_type khỏi câu lệnh INSERT.
    foreach ($lines as $line) {
        if (empty(trim($line))) continue;
        
        // Loại bỏ chuỗi , 'restaurant' hoặc , 'room_service' hoặc , 'both'
        // Cần thay bằng regex chắc chắn chỉ bắt các giá trị này dưới dạng enum service_type
        $line = preg_replace("/, '(restaurant|room_service|both)'/", "", $line);
        
        try {
            $db->exec($line);
            $inserted++;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                // Đã tồn tại (Duplicate entry) thì bỏ qua
            } else {
                echo "Lỗi khi thêm món: " . $e->getMessage() . "<br>\n";
            }
        }
    }
    
    echo "Khôi phục thành công {$inserted} món ăn bị thiếu vào cơ sở dữ liệu!\n";

} catch (PDOException $e) {
    echo "Lỗi kết nối CSDL: " . $e->getMessage() . "\n";
}
?>