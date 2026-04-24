<?php
// ============================================================
// Order Model — Aurora Restaurant
// ============================================================

class Order extends Model
{
    /** Mở order mới cho bàn */
    public function create(array $data): int
    {
        $tableId = $data['table_id'];
        $waiterId = $data['waiter_id'] ?? null;
        $shiftId = $data['shift_id'] ?? null;
        $guestCount = $data['guest_count'] ?? 1;
        $orderSource = $data['order_source'] ?? 'waiter';
        $note = $data['note'] ?? '';
        $sessionId = $data['session_id'] ?? null;

        $this->execute(
            "INSERT INTO orders (table_id, waiter_id, shift_id, guest_count, status, order_source, note, session_id, opened_at)
             VALUES (?, ?, ?, ?, 'open', ?, ?, ?, NOW())",
            [$tableId, $waiterId, $shiftId, $guestCount, $orderSource, $note, $sessionId]
        );
        return (int) $this->lastInsertId();
    }

    /** Lấy order đang mở của một bàn (không yêu cầu nhân viên) */
    public function findOpenOrderByTable(int $tableId): ?array
    {
        return $this->findOne(
            "SELECT * FROM orders WHERE table_id = ? AND status = 'open' ORDER BY opened_at DESC LIMIT 1",
            [$tableId]
        );
    }

    /** Lấy tất cả order đang mở của một session (thiết bị khách) */
    public function findBySessionId(string $sessionId): array
    {
        return $this->findAll(
            "SELECT o.*, t.name AS table_name, t.type AS table_type, t.status AS table_status, qt.qr_hash,
                    (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id AND oi.status != 'cancelled') AS total
             FROM orders o
             JOIN tables t ON t.id = o.table_id
             JOIN qr_tables qt ON qt.table_id = t.id
             WHERE o.session_id = ? AND o.status = 'open' AND qt.is_active = 1
             ORDER BY o.opened_at DESC",
            [$sessionId]
        );
    }

    /** Lấy order gần nhất của một bàn (kể cả đã đóng) */
    public function findLastOrderByTable(int $tableId): ?array
    {
        return $this->findOne(
            "SELECT * FROM orders WHERE table_id = ? ORDER BY id DESC LIMIT 1",
            [$tableId]
        );
    }

    /** Cập nhật số lượng khách */
    public function updateGuestCount(int $orderId, int $guestCount): void
    {
        $this->execute(
            "UPDATE orders SET guest_count = ? WHERE id = ?",
            [$guestCount, $orderId]
        );
    }

    /** Cập nhật session_id và nguồn order cho khách quét QR */
    public function updateSession(int $orderId, string $sessionId): void
    {
        $this->execute(
            "UPDATE orders SET session_id = ?, order_source = 'customer_qr' WHERE id = ?",
            [$sessionId, $orderId]
        );
    }

    /** Lấy toàn bộ các thiết bị (session) đang quét QR và các bàn tương ứng */
    public function getGroupedQrSessions(): array
    {
        $rows = $this->findAll(
            "SELECT o.session_id, o.id as order_id, o.opened_at, t.name as table_name, t.type as table_type,
                    (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id AND oi.status != 'cancelled') as total
             FROM orders o
             JOIN tables t ON t.id = o.table_id
             WHERE o.session_id IS NOT NULL AND o.status = 'open'
             ORDER BY o.session_id, o.opened_at DESC"
        );

        $sessions = [];
        foreach ($rows as $r) {
            $sid = $r['session_id'];
            if (!isset($sessions[$sid])) {
                $sessions[$sid] = [
                    'session_id' => $sid,
                    'tables' => [],
                    'total_all' => 0,
                    'since' => $r['opened_at']
                ];
            }
            $sessions[$sid]['tables'][] = $r;
            $sessions[$sid]['total_all'] += ($r['total'] ?? 0);
            // 'since' is the earliest opened_at
            if (strtotime($r['opened_at']) < strtotime($sessions[$sid]['since'])) {
                $sessions[$sid]['since'] = $r['opened_at'];
            }
        }
        return array_values($sessions);
    }
    public function appendNote(int $orderId, string $newNote): void
    {
        $order = $this->findById($orderId);
        if (!$order) return;

        $currentNote = $order['note'] ?? '';
        $updatedNote = empty($currentNote) ? $newNote : $currentNote . " | " . $newNote;

        $this->execute(
            "UPDATE orders SET note = ? WHERE id = ?",
            [$updatedNote, $orderId]
        );
    }

    /** Lấy order đang mở của một bàn (JOIN chi tiết) */
    public function getOpenByTable(int $tableId): ?array
    {
        return $this->findOne(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.table_id = ? AND o.status = 'open'
             ORDER BY o.opened_at DESC
             LIMIT 1",
            [$tableId]
        );
    }

    /** Lấy order theo ID */
    public function findById(int $id): ?array
    {
        return $this->findOne(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.id = ?",
            [$id]
        );
    }

    /** Lấy các món trong order (kèm note_options để hiển thị chip) */
    public function getItems(int $orderId): array
    {
        return $this->findAll(
            "SELECT oi.*, m.image, m.tags AS menu_tags,
                    m.note_options, m.note_options_en, m.name_en AS item_name_en
             FROM order_items oi
             JOIN menu_items m ON m.id = oi.menu_item_id
             WHERE oi.order_id = ?
             ORDER BY oi.created_at",
            [$orderId]
        );
    }

    /** Thêm món vào order (hoặc tăng số lượng nếu đã có) */
    public function addItem(int $orderId, array $data): void
    {
        $menuItemId = $data['menu_item_id'];
        $itemName = $data['item_name'] ?? null;
        $itemPrice = $data['item_price'] ?? 0;
        $qty = $data['quantity'] ?? 1;
        $note = $data['note'] ?? '';
        $status = $data['status'] ?? 'draft';
        $customerId = $data['customer_id'] ?? null;
        $submittedAt = $data['submitted_at'] ?? null;

        if (!$itemName || !$itemPrice) {
            $menuItem = $this->findOne("SELECT name, price FROM menu_items WHERE id = ?", [$menuItemId]);
            if (!$menuItem) {
                return;
            }
            $itemName = $menuItem['name'];
            $itemPrice = $menuItem['price'];
        }

        // Chỉ gộp chung với các món đang ở cùng trạng thái
        $existing = $this->findOne(
            "SELECT id, quantity FROM order_items
             WHERE order_id = ? AND menu_item_id = ? AND note = ? AND status = ?",
            [$orderId, $menuItemId, $note, $status]
        );

        if ($existing) {
            $this->execute(
                "UPDATE order_items SET quantity = quantity + ? WHERE id = ?",
                [$qty, $existing['id']]
            );
        } else {
            $order = $this->findById($orderId);
            $tableId = $order ? $order['table_id'] : null;
            
            $this->execute(
                "INSERT INTO order_items (order_id, table_id, menu_item_id, item_name, item_price, quantity, note, status, customer_id, submitted_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$orderId, $tableId, $menuItemId, $itemName, $itemPrice, $qty, $note, $status, $customerId, $submittedAt]
            );
        }
    }

    /** Cập nhật số lượng, nếu = 0 thì xóa */
    public function updateItem(int $itemId, int $qty): void
    {
        if ($qty <= 0) {
            $this->execute("DELETE FROM order_items WHERE id = ? AND (status = 'draft' OR status = 'pending')", [$itemId]);
        } else {
            $this->execute("UPDATE order_items SET quantity = ? WHERE id = ? AND (status = 'draft' OR status = 'pending')", [$qty, $itemId]);
        }
    }

    /** Xóa một dòng order item - Cho phép xóa mọi trạng thái nếu là nhân viên/admin */
    public function removeItem(int $itemId): void
    {
        $this->execute("DELETE FROM order_items WHERE id = ?", [$itemId]);
    }

    /** Đóng order & đóng bàn (Thanh toán) */
    public function close(int $orderId, string $paymentMethod): void
    {
        $this->execute(
            "UPDATE orders SET status = 'closed', closed_at = NOW(), 
             payment_method = ?, payment_status = 'paid' WHERE id = ?",
            [$paymentMethod, $orderId]
        );
    }

    public function cancel(int $orderId): void
    {
        // Use 'closed' instead of 'canceled' because the status ENUM only has 'open' and 'closed'
        $this->execute(
            "UPDATE orders SET status = 'closed', closed_at = NOW() WHERE id = ?",
            [$orderId]
        );

        try {
            // Attempt to update payment_status if the column exists, ignoring errors if it doesn't
            $this->execute(
                "UPDATE orders SET payment_status = 'canceled' WHERE id = ?",
                [$orderId]
            );
        } catch (\Exception $e) {
            // Do nothing if payment_status doesn't exist or doesn't support 'canceled'
        }
    }

    /** Lấy tất cả Order Đang Bận (Cho View Orders) */
    public function getAllOpen(): array
    {
        return $this->findAll(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name, t.area AS table_area,
                   (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total,
                   (SELECT COUNT(oi.id) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
             FROM orders o
             LEFT JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE o.status = 'open'
             ORDER BY o.opened_at DESC"
        );
    }

    /** Lấy tất cả Order Đang Bận + Mới thanh toán (Cho Admin Realtime) */
    public function getRealtimeOrders(): array
    {
        $sql = "SELECT o.*, u.name AS waiter_name, t.name AS table_name, t.area AS table_area,
                       (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total,
                       (SELECT COUNT(oi.id) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
                FROM orders o
                LEFT JOIN users u ON u.id = o.waiter_id
                JOIN tables t ON t.id = o.table_id
                WHERE is_realtime_hidden = 0 
                  AND (o.status = 'open' OR (o.status = 'closed' AND o.closed_at >= NOW() - INTERVAL 1 HOUR))
                ORDER BY CASE WHEN o.status = 'open' THEN 1 ELSE 2 END, o.opened_at DESC";
        return $this->findAll($sql);
    }

    /** Ẩn order khỏi màn hình realtime của Admin */
    public function dismissFromRealtime(int $orderId): void
    {
        $this->execute("UPDATE orders SET is_realtime_hidden = 1 WHERE id = ?", [$orderId]);
    }

    /** Xác nhận các món Draft hoặc Pending thành Confirmed (Xác nhận đặt món) */
    public function confirmDraftItems(int $orderId): void
    {
        $this->execute(
            "UPDATE order_items SET status = 'confirmed' WHERE order_id = ? AND (status = 'draft' OR status = 'pending')",
            [$orderId]
        );
    }

    /** Khách hàng gửi món -> Chuyển sang pending và chờ phục vụ xác nhận */
    public function confirmItemsToPending(int $orderId): void
    {
        $this->execute(
            "UPDATE order_items SET status = 'pending' WHERE order_id = ? AND status = 'draft'",
            [$orderId]
        );
    }

    /** Phục vụ xác nhận các món pending thành confirmed */
    public function confirmPendingItems(int $orderId): void
    {
        $this->execute(
            "UPDATE order_items SET status = 'confirmed' WHERE order_id = ? AND status = 'pending'",
            [$orderId]
        );
    }

    public function getHistoryByTable(int $tableId, int $limit = 5): array
    {
        return $this->findAll(
            "SELECT * FROM orders WHERE table_id = ? AND status = 'closed' ORDER BY created_at DESC LIMIT ?",
            [$tableId, $limit]
        );
    }

    /** Tính tổng tiền order */
    public function getTotal(int $orderId): float
    {
        $row = $this->findOne(
            "SELECT SUM(item_price * quantity) AS total FROM order_items WHERE order_id = ?",
            [$orderId]
        );
        return (float) ($row['total'] ?? 0);
    }

    /** Danh sách orders trong ngày (báo cáo) */
    public function getByDate(string $date): array
    {
        return $this->findAll(
            "SELECT o.*, u.name AS waiter_name, t.name AS table_name,
                    (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total
             FROM orders o
             JOIN users u ON u.id = o.waiter_id
             JOIN tables t ON t.id = o.table_id
             WHERE DATE(o.opened_at) = ?
             ORDER BY o.opened_at DESC",
            [$date]
        );
    }

    /** Stat báo cáo */
    public function getStatsByDateRange(string $from, string $to): array
    {
        return $this->findOne(
            "SELECT
                COUNT(*) AS total_orders,
                COUNT(DISTINCT table_id) AS tables_served,
                SUM((SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) AS revenue
             FROM orders o
             WHERE DATE(opened_at) BETWEEN ? AND ?",
            [$from, $to]
        ) ?? [];
    }

    /** Orders theo tháng (chart) */
    public function getDailyRevenue(string $from, string $to): array
    {
        return $this->findAll(
            "SELECT DATE(opened_at) AS day,
                    COUNT(*) AS orders,
                    SUM((SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) AS revenue
             FROM orders o
             WHERE DATE(opened_at) BETWEEN ? AND ?
             GROUP BY DATE(opened_at)
             ORDER BY day",
            [$from, $to]
        );
    }
    /** Sales History for Waiters/Admin with filters */
    public function getSalesHistory(array $filters = []): array
    {
        $sql = "SELECT o.*, u.name AS waiter_name, t.name AS table_name, t.area AS table_area,
                       (SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id) AS total,
                       (SELECT COUNT(oi.id) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
                FROM orders o
                LEFT JOIN users u ON u.id = o.waiter_id
                JOIN tables t ON t.id = o.table_id
                WHERE o.status = 'closed'";

        $params = [];

        // Filter by Date
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(o.opened_at) = ?";
            $params[] = $filters['date'];
        }
        // Filter by Month & Year
        elseif (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= " AND MONTH(o.opened_at) = ? AND YEAR(o.opened_at) = ?";
            $params[] = $filters['month'];
            $params[] = $filters['year'];
        }
        // Filter by Week (ISO week)
        elseif (!empty($filters['week']) && !empty($filters['year'])) {
            $sql .= " AND WEEK(o.opened_at, 1) = ? AND YEAR(o.opened_at) = ?";
            $params[] = $filters['week'];
            $params[] = $filters['year'];
        }
        // Filter by Year only
        elseif (!empty($filters['year'])) {
            $sql .= " AND YEAR(o.opened_at) = ?";
            $params[] = $filters['year'];
        }

        // Filter by Waiter (optional)
        if (!empty($filters['waiter_id'])) {
            $sql .= " AND o.waiter_id = ?";
            $params[] = $filters['waiter_id'];
        }

        $sql .= " ORDER BY o.closed_at DESC";

        // Apply limit and offset if provided
        if (isset($filters['limit']) && isset($filters['offset'])) {
            $sql .= " LIMIT " . (int) $filters['limit'] . " OFFSET " . (int) $filters['offset'];
        } else {
            $sql .= " LIMIT 500"; // Default limit
        }

        return $this->findAll($sql, $params);
    }

    /** Get stats for sales history (total count and total revenue) */
    public function getSalesHistoryStats(array $filters = []): array
    {
        $sql = "SELECT COUNT(o.id) as count, 
                       SUM((SELECT SUM(oi.item_price * oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as revenue
                FROM orders o
                WHERE o.status = 'closed'";

        $params = [];

        // Same filter logic as getSalesHistory
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(o.opened_at) = ?";
            $params[] = $filters['date'];
        } elseif (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= " AND MONTH(o.opened_at) = ? AND YEAR(o.opened_at) = ?";
            $params[] = $filters['month'];
            $params[] = $filters['year'];
        } elseif (!empty($filters['week']) && !empty($filters['year'])) {
            $sql .= " AND WEEK(o.opened_at, 1) = ? AND YEAR(o.opened_at) = ?";
            $params[] = $filters['week'];
            $params[] = $filters['year'];
        } elseif (!empty($filters['year'])) {
            $sql .= " AND YEAR(o.opened_at) = ?";
            $params[] = $filters['year'];
        }

        if (!empty($filters['waiter_id'])) {
            $sql .= " AND o.waiter_id = ?";
            $params[] = $filters['waiter_id'];
        }

        $result = $this->findOne($sql, $params);
        return [
            'count' => (int) ($result['count'] ?? 0),
            'revenue' => (float) ($result['revenue'] ?? 0)
        ];
    }

    /**
     * Split items from one order to a new/existing order
     * Used for table splitting functionality
     * 
     * @param int $sourceOrderId Source order ID
     * @param array $itemIds Array of order_item IDs to split
     * @param int $targetTableId Target table ID for the split items
     * @param int $targetOrderId Optional target order ID (if null, create new order)
     * @return array ['ok' => bool, 'new_order_id' => int, 'message' => string]
     */
    public function splitItems(int $sourceOrderId, array $itemIds, int $targetTableId, ?int $targetOrderId = null, ?int $guestCount = null): array
    {
        if (empty($itemIds)) {
            return ['ok' => false, 'message' => 'Không có món nào để tách'];
        }

        try {
            $this->db->beginTransaction();

            // Get source order info
            $sourceOrder = $this->findById($sourceOrderId);
            if (!$sourceOrder) {
                return ['ok' => false, 'message' => 'Không tìm thấy order nguồn'];
            }

            // Get items to split
            $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
            $items = $this->findAll(
                "SELECT * FROM order_items WHERE id IN ($placeholders)",
                $itemIds
            );

            if (count($items) !== count($itemIds)) {
                throw new \Exception('Không tìm thấy tất cả món cần tách');
            }

            // Create new order if target not specified
            $newOrderId = $targetOrderId;
            if (!$newOrderId) {
                $sourceTableName = $sourceOrder['table_name'] ?? ('Bàn ' . $sourceOrder['table_id']);
                $splitNote = "Tách từ " . $sourceTableName;
                
                // Use current user if no waiter in source
                $waiterId = $sourceOrder['waiter_id'] ?? (Auth::isLoggedIn() ? Auth::user()['id'] : null);
                
                // New Guest Count
                $finalGuestCount = $guestCount ?: max(1, (int)($sourceOrder['guest_count'] ?? 1));

                $this->execute(
                    "INSERT INTO orders (table_id, waiter_id, shift_id, guest_count, status, payment_status, note, order_source, opened_at, created_at) 
                     VALUES (?, ?, ?, ?, 'open', 'unpaid', ?, 'waiter', NOW(), NOW())",
                    [
                        $targetTableId, 
                        $waiterId, 
                        $sourceOrder['shift_id'] ?? ($_SESSION['user_shift_id'] ?? null),
                        $finalGuestCount,
                        $splitNote
                    ]
                );
                $newOrderId = (int)$this->lastInsertId();
            }

            // Move items to new order
            foreach ($items as $item) {
                // Update item to new order and table
                $this->execute(
                    "UPDATE order_items 
                     SET order_id = ?, table_id = ?, is_split_item = 1, split_from_item_id = ?, updated_at = NOW()
                     WHERE id = ?",
                    [$newOrderId, $targetTableId, $item['id'], $item['id']]
                );
            }

            $this->db->commit();

            return [
                'ok' => true, 
                'new_order_id' => $newOrderId,
                'message' => 'Tách bàn thành công'
            ];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Transfer item from one order to another
     * Used for moving items between merged tables
     * 
     * @param int $itemId Order item ID to transfer
     * @param int $targetOrderId Target order ID
     * @param int $targetTableId Target table ID
     * @return array ['ok' => bool, 'message' => string]
     */
    public function transferItem(int $itemId, int $targetOrderId, int $targetTableId): array
    {
        try {
            $item = $this->findOne("SELECT * FROM order_items WHERE id = ?", [$itemId]);
            if (!$item) {
                return ['ok' => false, 'message' => 'Không tìm thấy món'];
            }

            if ($item['status'] !== 'draft') {
                return ['ok' => false, 'message' => 'Chỉ có thể chuyển món nháp'];
            }

            $this->execute(
                "UPDATE order_items 
                 SET order_id = ?, table_id = ?, updated_at = NOW()
                 WHERE id = ?",
                [$targetOrderId, $targetTableId, $itemId]
            );

            return ['ok' => true, 'message' => 'Chuyển món thành công'];

        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get order items grouped by table
     * Used for merged table view
     * 
     * @param int $orderId Order ID
     * @return array Items grouped by table_id
     */
    public function getItemsByTable(int $orderId): array
    {
        $items = $this->findAll(
            "SELECT oi.*, t.name as table_name, t.area as table_area
             FROM order_items oi
             JOIN tables t ON oi.table_id = t.id
             WHERE oi.order_id = ?
             ORDER BY t.id, oi.created_at",
            [$orderId]
        );

        $grouped = [];
        foreach ($items as $item) {
            $tableId = $item['table_id'];
            if (!isset($grouped[$tableId])) {
                $grouped[$tableId] = [
                    'table_id' => $tableId,
                    'table_name' => $item['table_name'],
                    'table_area' => $item['table_area'],
                    'items' => [],
                    'total' => 0
                ];
            }
            $grouped[$tableId]['items'][] = $item;
            $grouped[$tableId]['total'] += $item['item_price'] * $item['quantity'];
        }

        return $grouped;
    }


}