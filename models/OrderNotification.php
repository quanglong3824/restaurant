<?php
// ============================================================
// OrderNotification Model — Aurora Restaurant
// ============================================================

class OrderNotification extends Model
{
    protected string $table = 'order_notifications';

    /** Tạo thông báo mới */
    public function create(array $data): int
    {
        $orderId = empty($data['order_id']) ? null : $data['order_id'];

        $this->execute(
            "INSERT INTO order_notifications 
             (order_id, table_id, notification_type, title, message) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $orderId,
                $data['table_id'],
                $data['notification_type'],
                $data['title'],
                $data['message']
            ]
        );
        return (int) $this->lastInsertId();
    }

    /** Lấy danh sách thông báo mới nhất kèm chi tiết bàn */
    public function getRecent(int $limit = 50): array
    {
        return $this->findAll(
            "SELECT n.*, t.name as table_name, t.area as table_area,
                    o.status as order_status
             FROM order_notifications n
             JOIN tables t ON n.table_id = t.id
             LEFT JOIN orders o ON n.order_id = o.id
             ORDER BY n.is_read ASC, n.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    /** Lấy danh sách thông báo phân trang (hỗ trợ lọc theo type + status) */
    public function getPaged(int $page, int $limit, string $type = 'all', string $status = ''): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT n.*, t.name as table_name, t.area as table_area,
                       o.status as order_status
                FROM order_notifications n
                JOIN tables t ON n.table_id = t.id
                LEFT JOIN orders o ON n.order_id = o.id";
        
        $conditions = [];
        $params = [];

        // Lọc theo notification_type
        if ($type !== 'all') {
            $conditions[] = "n.notification_type = ?";
            $params[] = $type;
        }

        // Lọc theo trạng thái đọc
        if ($status === 'unread') {
            $conditions[] = "n.is_read = 0";
        } elseif ($status === 'read') {
            $conditions[] = "n.is_read = 1";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY n.is_read ASC, n.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->findAll($sql, $params);
    }

    /** Đếm tổng số thông báo (hỗ trợ lọc theo type + status) */
    public function countAll(string $type = 'all', string $status = ''): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM order_notifications";
        $conditions = [];
        $params = [];

        if ($type !== 'all') {
            $conditions[] = "notification_type = ?";
            $params[] = $type;
        }

        if ($status === 'unread') {
            $conditions[] = "is_read = 0";
        } elseif ($status === 'read') {
            $conditions[] = "is_read = 1";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $row = $this->findOne($sql, $params);
        return (int)($row['cnt'] ?? 0);
    }

    /** Đếm số thông báo chưa đọc */
    public function countUnread(): int
    {
        $row = $this->findOne("SELECT COUNT(*) as cnt FROM order_notifications WHERE is_read = 0");
        return (int)($row['cnt'] ?? 0);
    }

    /** Đếm số thông báo chưa đọc theo loại */
    public function countUnreadByType(string $type): int
    {
        $row = $this->findOne("SELECT COUNT(*) as cnt FROM order_notifications WHERE is_read = 0 AND notification_type = ?", [$type]);
        return (int)($row['cnt'] ?? 0);
    }

    /** Đánh dấu đã đọc */
    public function markAsRead(int $id, int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE id = ?",
            [$userId, $id]
        );
    }

    /** Đánh dấu tất cả đã đọc cho một nhân viên */
    public function markAllAsRead(int $userId): void
    {
        $this->execute(
            "UPDATE order_notifications 
             SET is_read = 1, read_at = NOW(), read_by = ? 
             WHERE is_read = 0",
            [$userId]
        );
    }

    /** Xóa thông báo cũ (giữ lại 200 cái gần nhất) */
    public function cleanup(): void
    {
        $this->execute(
            "DELETE FROM order_notifications 
             WHERE id NOT IN (
                SELECT id FROM (
                    SELECT id FROM order_notifications 
                    ORDER BY created_at DESC LIMIT 200
                ) tmp
             )"
        );
    }

    /** Lấy thông báo chưa đọc theo danh sách table_id */
    public function getUnreadByTableIds(array $tableIds): array
    {
        if (empty($tableIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($tableIds), '?'));
        $rows = $this->findAll(
            "SELECT table_id, notification_type, COUNT(*) as count
             FROM order_notifications
             WHERE table_id IN ({$placeholders}) AND is_read = 0
             GROUP BY table_id, notification_type",
            $tableIds
        );

        // Group by table_id
        $result = [];
        foreach ($rows as $row) {
            $tid = $row['table_id'];
            if (!isset($result[$tid])) {
                $result[$tid] = [];
            }
            $result[$tid][] = [
                'type' => $row['notification_type'],
                'count' => (int) $row['count']
            ];
        }

        return $result;
    }
}
