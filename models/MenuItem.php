<?php
// ============================================================
// MenuItem Model — Aurora Restaurant
// ============================================================

class MenuItem extends Model
{
    /** Kiểm tra cột có tồn tại trong bảng không */
    private function hasColumn(string $column): bool
    {
        try {
            $db = getDB();
            $result = $db->query("SHOW COLUMNS FROM menu_items LIKE '{$column}'")->fetch();
            return (bool) $result;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** Tất cả món kể cả inactive (admin) */
    public function getAll(): array
    {
        return $this->findAll(
            "SELECT i.*, c.name AS category_name
             FROM menu_items i
             LEFT JOIN menu_categories c ON c.id = i.category_id
             ORDER BY c.sort_order, i.sort_order, i.name"
        );
    }

    /** Tất cả món đang active (không phân biệt type) */
    public function getAllActive(string $serviceType = ''): array
    {
        $where = "WHERE i.is_active = 1";
        $params = [];
        if ($serviceType) {
            // Also match empty string for backward compatibility with legacy data
            $where .= " AND (i.service_type IN (?, 'both') OR i.service_type = '')";
            $params[] = $serviceType;
        }

        // Kiểm tra cột menu_type có tồn tại không
        $hasMenuType = $this->hasColumn('menu_type');
        
        if ($hasMenuType) {
            return $this->findAll(
                "SELECT i.*, c.name AS category_name, c.menu_type
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 $where
                 ORDER BY c.sort_order, i.sort_order, i.name",
                $params
            );
        } else {
            return $this->findAll(
                "SELECT i.*, c.name AS category_name
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 $where
                 ORDER BY c.sort_order, i.sort_order, i.name",
                $params
            );
        }
    }

    /** Món đang hiển thị, kèm category (waiter), lọc theo menu_type */
    public function getActiveByType(string $type = '', string $serviceType = ''): array
    {
        $where = "WHERE i.is_active = 1";
        $params = [];
        
        // Kiểm tra cột menu_type có tồn tại không
        $hasMenuType = $this->hasColumn('menu_type');
        
        if ($type && $hasMenuType) {
            $where .= " AND i.menu_type = ?";
            $params[] = $type;
        }
        if ($serviceType) {
            // Also match empty string for backward compatibility with legacy data
            $where .= " AND (i.service_type IN (?, 'both') OR i.service_type = '')";
            $params[] = $serviceType;
        }

        if ($hasMenuType) {
            return $this->findAll(
                "SELECT i.*, c.name AS category_name, c.menu_type
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 $where
                 ORDER BY c.sort_order, i.sort_order, i.name",
                $params
            );
        } else {
            return $this->findAll(
                "SELECT i.*, c.name AS category_name
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 $where
                 ORDER BY c.sort_order, i.sort_order, i.name",
                $params
            );
        }
    }

    /** Nhóm theo category cho waiter menu, có lọc type */
    public function getGroupedByCategory(string $type = '', string $serviceType = ''): array
    {
        $rows = $this->getActiveByType($type, $serviceType);
        $grouped = [];
        foreach ($rows as $row) {
            $cat = $row['category_name'] ?? 'Khác';
            $grouped[$cat][] = $row;
        }
        return $grouped;
    }

    public function findById(int $id): ?array
    {
        // Kiểm tra cột menu_type có tồn tại không
        $hasMenuType = $this->hasColumn('menu_type');
        
        if ($hasMenuType) {
            return $this->findOne(
                "SELECT i.*, c.name AS category_name, c.menu_type
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 WHERE i.id = ?",
                [$id]
            );
        } else {
            return $this->findOne(
                "SELECT i.*, c.name AS category_name
                 FROM menu_items i
                 LEFT JOIN menu_categories c ON c.id = i.category_id
                 WHERE i.id = ?",
                [$id]
            );
        }
    }

    public function create(array $data): int
    {
        // Kiểm tra cột menu_type có tồn tại không
        $hasMenuType = $this->hasColumn('menu_type');

        if ($hasMenuType) {
            $this->execute(
                "INSERT INTO menu_items
                 (category_id, name, name_en, description, price, image, is_available, is_active, tags, note_options, note_options_en, sort_order, stock, menu_type)
                 VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, ?, ?, ?, ?, ?)",
                [
                    $data['category_id'],
                    $data['name'],
                    $data['name_en'] ?? null,
                    $data['description'] ?? null,
                    $data['price'],
                    $data['image'] ?? null,
                    $data['tags'] ?? null,
                    $data['note_options'] ?? null,
                    $data['note_options_en'] ?? null,
                    $data['sort_order'] ?? 0,
                    $data['stock'] ?? -1,
                    $data['menu_type'] ?? 'asia',
                ]
            );
        } else {
            // Fallback khi cột menu_type chưa tồn tại
            $this->execute(
                "INSERT INTO menu_items
                 (category_id, name, name_en, description, price, image, is_available, is_active, tags, note_options, note_options_en, sort_order, stock)
                 VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, ?, ?, ?, ?)",
                [
                    $data['category_id'],
                    $data['name'],
                    $data['name_en'] ?? null,
                    $data['description'] ?? null,
                    $data['price'],
                    $data['image'] ?? null,
                    $data['tags'] ?? null,
                    $data['note_options'] ?? null,
                    $data['note_options_en'] ?? null,
                    $data['sort_order'] ?? 0,
                    $data['stock'] ?? -1,
                ]
            );
        }
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        // Kiểm tra cột menu_type có tồn tại không
        $hasMenuType = $this->hasColumn('menu_type');

        if ($hasMenuType) {
            $this->execute(
                "UPDATE menu_items
                 SET category_id = ?, name = ?, name_en = ?, description = ?,
                     price = ?, tags = ?, note_options = ?, note_options_en = ?,
                     sort_order = ?, is_active = ?, stock = ?, menu_type = ?
                 WHERE id = ?",
                [
                    $data['category_id'],
                    $data['name'],
                    $data['name_en'] ?? null,
                    $data['description'] ?? null,
                    $data['price'],
                    $data['tags'] ?? null,
                    $data['note_options'] ?? null,
                    $data['note_options_en'] ?? null,
                    $data['sort_order'] ?? 0,
                    $data['is_active'] ?? 1,
                    $data['stock'] ?? -1,
                    $data['menu_type'] ?? 'asia',
                    $id,
                ]
            );
        } else {
            // Fallback khi cột menu_type chưa tồn tại
            $this->execute(
                "UPDATE menu_items
                 SET category_id = ?, name = ?, name_en = ?, description = ?,
                     price = ?, tags = ?, note_options = ?, note_options_en = ?,
                     sort_order = ?, is_active = ?, stock = ?
                 WHERE id = ?",
                [
                    $data['category_id'],
                    $data['name'],
                    $data['name_en'] ?? null,
                    $data['description'] ?? null,
                    $data['price'],
                    $data['tags'] ?? null,
                    $data['note_options'] ?? null,
                    $data['note_options_en'] ?? null,
                    $data['sort_order'] ?? 0,
                    $data['is_active'] ?? 1,
                    $data['stock'] ?? -1,
                    $id,
                ]
            );
        }
    }
    public function updateImage(int $id, string $image): void
    {
        $this->execute("UPDATE menu_items SET image = ? WHERE id = ?", [$image, $id]);
    }

    /** Toggle hết hàng / còn hàng */
    public function toggleAvailable(int $id): void
    {
        $this->execute(
            "UPDATE menu_items SET is_available = NOT is_available WHERE id = ?",
            [$id]
        );
    }

    /** Toggle hiển thị / ẩn */
    public function toggleActive(int $id): void
    {
        $this->execute(
            "UPDATE menu_items SET is_active = NOT is_active WHERE id = ?",
            [$id]
        );
    }

    public function delete(int $id): void
    {
        $this->execute("DELETE FROM menu_items WHERE id = ?", [$id]);
    }

    /** Top món được gọi nhiều nhất */
    public function getTopItems(int $limit = 10, string $from = '', string $to = ''): array
    {
        $where = '';
        $params = [];
        if ($from && $to) {
            $where = "AND DATE(o.opened_at) BETWEEN ? AND ?";
            $params = [$from, $to];
        }
        return $this->findAll(
            "SELECT i.name, SUM(oi.quantity) AS total_qty
             FROM order_items oi
             JOIN menu_items i ON i.id = oi.menu_item_id
             JOIN orders o ON o.id = oi.order_id
             WHERE 1=1 {$where}
             GROUP BY oi.menu_item_id
             ORDER BY total_qty DESC
             LIMIT ?",
            array_merge($params, [$limit])
        );
    }

    /** Add gallery image for menu item (placeholder for future gallery feature) */
    public function addGalleryImage(int $itemId, string $imagePath): bool
    {
        // Placeholder: Gallery feature not fully implemented yet
        // This method can be extended to store multiple images per menu item
        return true;
    }
}
