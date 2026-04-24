<?php
// ============================================================
// Table Model — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/ActivityLog.php';

class Table extends Model
{
    protected string $table = 'tables';

    /** Lấy bàn theo ID */
    public function findById(int $id): ?array
    {
        return $this->findOne(
            "SELECT t.*, p.name as parent_name
             FROM tables t
             LEFT JOIN tables p ON t.parent_id = p.id
             WHERE t.id = ?",
            [$id]
        );
    }

    /** Lấy tất cả bàn và phòng đang hoạt động */
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT t.*, o.note as order_note
             FROM tables t
             LEFT JOIN orders o ON o.table_id = t.id AND o.status = 'open'
             WHERE t.is_active = 1
             ORDER BY t.area, t.sort_order, t.name"
        );
    }

    /** Tất cả bàn đang active theo loại, sắp xếp theo khu vực + thứ tự */
    public function getAllByType(string $type = 'table'): array
    {
        // Kiểm tra xem cột type có tồn tại không để tránh lỗi 500 nếu DB chưa setup
        $check = $this->findOne("SHOW COLUMNS FROM `tables` LIKE 'type'");
        $whereType = $check ? "AND t.type = ?" : "";
        $params = $check ? [$type] : [];

        return $this->findAll(
            "SELECT t.*, o.note as order_note
             FROM tables t
             LEFT JOIN orders o ON o.table_id = t.id AND o.status = 'open'
             WHERE t.is_active = 1 $whereType
             ORDER BY 
                CASE t.area
                    WHEN 'A1' THEN 1
                    WHEN 'B1' THEN 2
                    WHEN 'C1' THEN 3
                    WHEN 'VIP 1' THEN 4
                    WHEN 'VIP 2' THEN 5
                    WHEN 'VIP 3' THEN 6
                    WHEN 'VIP 4' THEN 7
                    WHEN 'Âu' THEN 8
                    ELSE 99
                END,
                t.sort_order, t.name",
            $params
        );
    }

    /** Lấy tất cả bàn cho Admin theo loại */
    public function getAllForAdminByType(string $type = 'table'): array
    {
        $check = $this->findOne("SHOW COLUMNS FROM `tables` LIKE 'type'");
        $whereType = $check ? "WHERE t.type = ?" : "";
        $params = $check ? [$type] : [];

        // Kiểm tra cột parent_id
        $checkParent = $this->findOne("SHOW COLUMNS FROM `tables` LIKE 'parent_id'");
        $selectParent = $checkParent ? "p.name as parent_name," : "'' as parent_name,";
        $joinParent = $checkParent ? "LEFT JOIN tables p ON t.parent_id = p.id" : "";

        return $this->findAll(
            "SELECT t.*, $selectParent 
                    qr.qr_hash as qr_token, qr.is_printed, qr.scan_count,
                    (SELECT COUNT(*) FROM orders o WHERE o.table_id = t.id AND o.status = 'open') as has_order,
                    (SELECT COUNT(*) FROM order_items oi JOIN orders o2 ON oi.order_id = o2.id WHERE o2.table_id = t.id AND o2.status = 'open') as items_count
             FROM tables t
             $joinParent
             LEFT JOIN qr_tables qr ON t.id = qr.table_id AND qr.is_active = 1
             $whereType
             ORDER BY 
                CASE t.area
                    WHEN 'A1' THEN 1
                    WHEN 'B1' THEN 2
                    WHEN 'C1' THEN 3
                    WHEN 'VIP 1' THEN 4
                    WHEN 'VIP 2' THEN 5
                    WHEN 'VIP 3' THEN 6
                    WHEN 'VIP 4' THEN 7
                    WHEN 'Âu' THEN 8
                    ELSE 99
                END,
                t.sort_order, t.name",
            $params
        );
    }

    /** Lấy tất cả bàn đang trống (không phải bàn đang ghép vào bàn khác) */
    public function getAvailable(): array
    {
        return $this->findAll(
            "SELECT * FROM tables 
             WHERE is_active = 1 AND status = 'available' AND parent_id IS NULL 
             ORDER BY area, sort_order, name"
        );
    }

    /** Nhóm bàn theo khu vực (loại bỏ bàn đang ghép nếu cần, hoặc hiển thị lồng nhau) */
    public function getAllGroupedByArea(string $type = 'table'): array
    {
        $rows = $this->getAllByType($type);
        $grouped = [];
        foreach ($rows as $row) {
            $area = $row['area'] ?? 'Chung';
            $grouped[$area][] = $row;
        }
        return $grouped;
    }

    /** Ghép bàn: childId ghép vào parentId */
    public function mergeTable(int $childId, int $parentId): bool
    {
        // Kiểm tra xem bàn con có đang có order không
        $inUse = $this->findOne(
            "SELECT id FROM orders WHERE table_id = ? AND status = 'open' LIMIT 1",
            [$childId]
        );
        if ($inUse)
            return false;

        $this->execute(
            "UPDATE tables SET parent_id = ?, status = 'occupied', updated_at = NOW() WHERE id = ?",
            [$parentId, $childId]
        );
        return true;
    }

    /** Hủy ghép bàn */
    public function unmergeTable(int $childId): void
    {
        $this->execute(
            "UPDATE tables SET parent_id = NULL, updated_at = NOW() WHERE id = ?",
            [$childId]
        );
    }

    /** Lấy tên hiển thị đầy đủ (bao gồm cả các bàn đang ghép chung) */
    public function getFullDisplayName(int $tableId): string
    {
        $table = $this->findById($tableId);
        if (!$table)
            return "N/A";

        // Nếu bàn này là con, lấy bàn cha của nó
        $effectiveParentId = $table['parent_id'] ?: $table['id'];

        // Lấy tất cả bàn trong nhóm (cha + con)
        $group = $this->findAll(
            "SELECT name FROM tables 
             WHERE id = ? OR parent_id = ? 
             ORDER BY name ASC",
            [$effectiveParentId, $effectiveParentId]
        );

        if (count($group) <= 1) {
            return $table['name'];
        }

        if (count($group) > 3) {
            return $table['name'] . ' (+' . (count($group) - 1) . ' bàn)';
        }

        $names = array_column($group, 'name');
        return implode(' + ', $names);
    }

    /** Lấy các bàn đang ghép vào bàn cha */
    public function getMergedTables(int $parentId): array
    {
        return $this->findAll("SELECT * FROM tables WHERE parent_id = ?", [$parentId]);
    }

    /** Mở bàn: đổi status → occupied */
    public function open(int $id): void
    {
        $this->execute(
            "UPDATE tables SET status = 'occupied', updated_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    /** Đóng bàn: đổi status → available (Xử lý cả bàn ghép) */
    public function close(int $id): void
    {
        // Lấy danh sách các bàn đang ghép vào bàn này (nếu có)
        $children = $this->findAll("SELECT id FROM tables WHERE parent_id = ?", [$id]);

        // 1. Chuyển bàn hiện tại về trống và xóa liên kết ghép (nếu có)
        $this->execute(
            "UPDATE tables SET parent_id = NULL, status = 'available', updated_at = NOW() WHERE id = ?",
            [$id]
        );

        // 2. Chuyển tất cả bàn con về trạng thái trống
        foreach ($children as $child) {
            $this->execute(
                "UPDATE tables SET parent_id = NULL, status = 'available', updated_at = NOW() WHERE id = ?",
                [$child['id']]
            );
        }
    }

    /** Thêm bàn mới */
    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO tables (name, area, capacity, type, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, 1)",
            [
                $data['name'],
                $data['area'] ?? null,
                $data['capacity'] ?? 4,
                $data['type'] ?? 'table',
                $data['sort_order'] ?? 0,
            ]
        );
        return (int) $this->lastInsertId();
    }

    /** Cập nhật bàn */
    public function update(int $id, array $data): void
    {
        $this->execute(
            "UPDATE tables SET name = ?, area = ?, capacity = ?, type = ?, sort_order = ?, is_active = ?
             WHERE id = ?",
            [
                $data['name'],
                $data['area'] ?? null,
                $data['capacity'] ?? 4,
                $data['type'] ?? 'table',
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id,
            ]
        );
    }

    /** Xóa bàn (chỉ khi không có order đang open) */
    public function delete(int $id): bool
    {
        $inUse = $this->findOne(
            "SELECT id FROM orders WHERE table_id = ? AND status = 'open' LIMIT 1",
            [$id]
        );
        if ($inUse)
            return false;

        $this->execute("DELETE FROM tables WHERE id = ?", [$id]);
        return true;
    }

    /** Tự động đồng bộ trạng thái bàn dựa trên Order thực tế (Sửa lỗi bàn bị kẹt 'occupied') */
    public function syncStatuses(): void
    {
        // 1. Giải phóng các bàn đang 'occupied' nhưng không có đơn hàng 'open' nào
        $this->execute(
            "UPDATE tables t 
             LEFT JOIN orders o ON o.table_id = t.id AND o.status = 'open'
             SET t.status = 'available'
             WHERE t.status = 'occupied' AND o.id IS NULL AND t.parent_id IS NULL"
        );

        // 2. Giải phóng các bàn con (merged child) nếu bàn cha không có đơn hàng 'open'
        $this->execute(
            "UPDATE tables t
             JOIN tables p ON t.parent_id = p.id
             LEFT JOIN orders o ON o.table_id = p.id AND o.status = 'open'
             SET t.status = 'available', t.parent_id = NULL
             WHERE p.status = 'available' OR o.id IS NULL"
        );

        // 3. Tự động huỷ bàn mở quá 5 phút nhưng không có món nào (áp dụng cho TẤT CẢ bàn)
        // Ghi nhật ký hoạt động với action 'cancel'
        $stuckSessions = $this->findAll(
            "SELECT o.table_id, o.id as order_id, o.session_id, o.waiter_id, o.order_source, t.name as table_name
             FROM orders o 
             JOIN tables t ON o.table_id = t.id
             WHERE o.status = 'open' 
             AND t.status = 'occupied'
             AND o.opened_at < NOW() - INTERVAL 5 MINUTE
             AND o.id NOT IN (SELECT DISTINCT order_id FROM order_items)"
        );

        // Khởi tạo ActivityLog model để ghi nhật ký
        $activityLog = new ActivityLog();

        foreach ($stuckSessions as $s) {
            // Đóng order trống này và trả bàn về trống
            $cancelNote = 'Hệ thống tự động huỷ do không đặt món sau 5 phút';
            $this->execute("UPDATE orders SET status = 'closed', note = ?, payment_status = 'canceled', closed_at = NOW() WHERE id = ?", [$cancelNote, $s['order_id']]);
            $this->close($s['table_id']);
            
            // Ghi nhật ký hoạt động - action 'cancel', entity 'table'
            $activityLog->log(
                ActivityLog::ACTION_CANCEL,
                'table',
                $s['table_id'],
                [
                    'order_id' => $s['order_id'],
                    'table_name' => $s['table_name'],
                    'reason' => 'auto_cancel_no_items',
                    'message' => $cancelNote,
                    'order_source' => $s['order_source'],
                    'waiter_id' => $s['waiter_id'],
                    'session_id' => $s['session_id']
                ],
                ActivityLog::LEVEL_NOTICE,
                null // user_id = null vì đây là hệ thống tự động
            );
            
            // Vô hiệu hoá session của khách (nếu có) để thiết bị bị logout
            if (!empty($s['session_id'])) {
                $this->execute("UPDATE customer_sessions SET is_active = 0 WHERE session_id = ?", [$s['session_id']]);
            }
        }
    }

    /** Đếm theo trạng thái (Bàn vật lý chính xác) */
    public function countByStatus(): array
    {
        // available: Bàn active, status available
        $available = $this->findOne(
            "SELECT COUNT(*) as cnt FROM tables 
             WHERE is_active = 1 AND status = 'available'"
        )['cnt'];

        // occupied: Bàn active, status occupied
        $occupied = $this->findOne(
            "SELECT COUNT(*) as cnt FROM tables 
             WHERE is_active = 1 AND status = 'occupied'"
        )['cnt'];

        return [
            'available' => (int) $available,
            'occupied' => (int) $occupied
        ];
    }

    /** Lấy tất cả bàn cho Admin (bao gồm cả inactive) */
    public function getAllForAdmin(): array
    {
        return $this->findAll(
            "SELECT t.*, p.name as parent_name, 
                    qr.qr_hash as qr_token, qr.is_printed, qr.scan_count,
                    (SELECT COUNT(*) FROM orders o WHERE o.table_id = t.id AND o.status = 'open') as has_order,
                    (SELECT COUNT(*) FROM order_items oi JOIN orders o2 ON oi.order_id = o2.id WHERE o2.table_id = t.id AND o2.status = 'open') as items_count
             FROM tables t
             LEFT JOIN tables p ON t.parent_id = p.id
             LEFT JOIN qr_tables qr ON t.id = qr.table_id AND qr.is_active = 1
             ORDER BY 
                CASE t.area
                    WHEN 'A1' THEN 1
                    WHEN 'B1' THEN 2
                    WHEN 'C1' THEN 3
                    WHEN 'VIP 1' THEN 4
                    WHEN 'VIP 2' THEN 5
                    WHEN 'VIP 3' THEN 6
                    WHEN 'VIP 4' THEN 7
                    WHEN 'Âu' THEN 8
                    ELSE 99
                END,
                t.sort_order, t.name"
        );
    }
    /** Ghép nhiều khu vực: chọn tất cả bàn trong các khu này, 1 bàn làm cha, còn lại làm con, status = occupied */
    public function mergeAreas(array $areas): int
    {
        if (empty($areas)) {
            return 0;
        }

        // Tạo câu hỏi IN(?, ?, ...)
        $placeholders = str_repeat('?,', count($areas) - 1) . '?';

        // Lấy tất cả bàn trong các khu vực được chọn
        // Không ghép các bàn đang bận của order khác (nếu đang open thì cẩn thận, nhưng yêu cầu là ghép khu)
        // Yêu cầu "lấy 1 bàn bất kì làm main", và tất cả đổi màu (occupied)
        $tables = $this->findAll(
            "SELECT id FROM tables WHERE is_active = 1 AND area IN ($placeholders) ORDER BY id ASC",
            $areas
        );

        if (empty($tables)) {
            return 0;
        }

        // Bàn đầu tiên làm main
        $mainTableId = $tables[0]['id'];

        // Cập nhật bàn chính: thành bận và không có cha
        $this->execute(
            "UPDATE tables SET parent_id = NULL, status = 'occupied', updated_at = NOW() WHERE id = ?",
            [$mainTableId]
        );

        // Các bàn còn lại làm con của bàn chính
        for ($i = 1; $i < count($tables); $i++) {
            $childId = $tables[$i]['id'];
            $this->execute(
                "UPDATE tables SET parent_id = ?, status = 'occupied', updated_at = NOW() WHERE id = ?",
                [$mainTableId, $childId]
            );
        }

        return (int) $mainTableId;
    }

    /** Xóa tách toàn bộ các bàn trong khu vực (hủy ghép và reset bàn) */
    public function unmergeAreas(array $areas): void
    {
        if (empty($areas)) {
            return;
        }

        $placeholders = str_repeat('?,', count($areas) - 1) . '?';

        $tables = $this->findAll(
            "SELECT id FROM tables WHERE is_active = 1 AND area IN ($placeholders)",
            $areas
        );

        if (empty($tables)) {
            return;
        }

        $tableIds = array_column($tables, 'id');
        $idPlaceholders = str_repeat('?,', count($tableIds) - 1) . '?';

        $this->execute(
            "UPDATE orders SET status = 'closed', closed_at = NOW() WHERE status = 'open' AND table_id IN ($idPlaceholders)",
            $tableIds
        );

        $this->execute(
            "UPDATE tables SET parent_id = NULL, status = 'available', updated_at = NOW() WHERE id IN ($idPlaceholders)",
            $tableIds
        );
    }
}
