<?php
// ============================================================
// OrderController — Waiter: Manage Order Items
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuSet.php';
require_once BASE_PATH . '/models/ActivityLog.php';
require_once BASE_PATH . '/models/OrderNotification.php';

class OrderController extends Controller
{
    private Order $orderModel;
    private Table $tableModel;
    private MenuItem $menuModel;
    private MenuSet $setModel;
    private ActivityLog $activityLog;
    private OrderNotification $notificationModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->tableModel = new Table();
        $this->menuModel  = new MenuItem();
        $this->setModel   = new MenuSet();
        $this->activityLog = new ActivityLog();
        $this->notificationModel = new OrderNotification();
    }

    /** Parse menu_tags -> item_options array và format giá cho JS */
    private function formatItemsForJs(array &$items): void
    {
        foreach ($items as &$it) {
            $it['price_fmt']    = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
            // Parse opt: tags -> item_options array
            $tags = array_map('trim', explode(',', $it['menu_tags'] ?? ''));
            $it['item_options'] = array_values(array_filter(array_map(
                fn($t) => strpos($t, 'opt:') === 0 ? substr($t, 4) : null,
                $tags
            )));
        }
    }

    /** GET /orders?table_id=&order_id= — Xem order của bàn, hoặc Danh sách tất cả bàn bận */
    public function index(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $tableId = (int) $this->input('table_id');

        if ($tableId === 0) {
            // View Danh sách Bàn Đang Bận
            $openOrders = $this->orderModel->getAllOpen();
            $allTables = $this->tableModel->getAll();
            $allAreas = array_unique(array_filter(array_column($allTables, 'area')));
            sort($allAreas);

            // Lấy pending notifications cho tất cả bàn đang mở
            $tableIds = array_column($openOrders, 'table_id');
            $notificationsByTable = $this->notificationModel->getUnreadByTableIds($tableIds);

            $this->view('layouts/waiter', [
                'view' => 'orders/list',
                'pageTitle' => 'Danh sách Bàn Đang Order',
                'orders' => $openOrders,
                'areas' => $allAreas,
                'notificationsByTable' => $notificationsByTable
            ]);
            return;
        }

        $orderId = (int) $this->input('order_id');

        $table = $this->tableModel->findById($tableId);
        if (!$table) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn không tồn tại.'];
            $this->redirect('/tables');
        }

        // Lấy order đang mở (ưu tiên order_id nếu có)
        $order = $orderId
            ? $this->orderModel->findById($orderId)
            : $this->orderModel->getOpenByTable($tableId);

        // Nếu order được lấy theo ID cụ thể mà có trạng thái 'closed', 
        // thì reset để hiển thị empty state
        if ($orderId && $order && $order['status'] === 'closed') {
            $order = null; // Reset order để hiển thị empty state
        }

        $items = $order ? $this->orderModel->getItems($order['id']) : [];
        $total = $order ? $this->orderModel->getTotal($order['id']) : 0;
        $tableDisplayName = $this->tableModel->getFullDisplayName($tableId);
        $grouped = $this->tableModel->getAllGroupedByArea();

        // Tích hợp logic gợi ý ghép bàn liên tục (persistent banner)
        $mergeSuggestion = null;
        if ($order && empty($table['parent_id']) && (int) $order['guest_count'] > 0) {
            // Tính tổng sức chứa (bao gồm bàn chính và các bàn ghép)
            $totalCapacity = (int) $table['capacity'];
            $children = $this->tableModel->getMergedTables($tableId);
            foreach ($children as $child) {
                $totalCapacity += (int) $child['capacity'];
            }

            if ((int) $order['guest_count'] > $totalCapacity) {
                $extraGuests = (int) $order['guest_count'] - $totalCapacity;
                $tableNeeded = ceil($extraGuests / 4);

                $db = getDB();
                $stmt = $db->prepare(
                    "SELECT name FROM tables 
                     WHERE area = ? AND status = 'available' AND parent_id IS NULL AND id != ?
                     ORDER BY sort_order, name
                     LIMIT ?"
                );
                $stmt->bindValue(1, $table['area']);
                $stmt->bindValue(2, $tableId, PDO::PARAM_INT);
                $stmt->bindValue(3, $tableNeeded, PDO::PARAM_INT);
                $stmt->execute();
                $availableInArea = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($availableInArea)) {
                    $availableNames = array_column($availableInArea, 'name');
                    $suggestionStr = implode(', ', $availableNames);
                    $mergeSuggestion = "Số lượng khách ({$order['guest_count']}) đang vượt quá sức chứa ({$totalCapacity}). Gợi ý: Hãy bấm 'Ghép bàn' thêm với bàn <strong>{$suggestionStr}</strong> ở cùng khu vực!";
                } else {
                    $mergeSuggestion = "Số lượng khách ({$order['guest_count']}) đang vượt quá sức chứa ({$totalCapacity}). Gợi ý: Hãy bấm 'Ghép bàn' thêm <strong>{$tableNeeded} bàn nữa</strong> để đủ chỗ ngồi!";
                }
            }
        }

        $this->view('layouts/waiter', [
            'view' => 'orders/index',
            'pageTitle' => 'Order — ' . $tableDisplayName,
            'table' => $table,
            'table_display_name' => $tableDisplayName,
            'order' => $order,
            'items' => $items,
            'total' => $total,
            'grouped' => $grouped,
            'mergeSuggestion' => $mergeSuggestion,
        ]);
    }

    /** POST /orders/update-guest-count — Cập nhật số khách */
    public function updateGuestCount(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['status'] !== 'open') {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ.']);
        }

        $this->orderModel->updateGuestCount($orderId, $guestCount);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'order',
            $orderId,
            ['guest_count' => $guestCount],
            ActivityLog::LEVEL_NOTICE
        );
        
        $this->json(['ok' => true, 'message' => 'Đã cập nhật số khách.']);
    }

    /** POST /orders/add — Thêm món vào order */
    public function addItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $tableId = (int) $this->input('table_id');
        $menuItemId = (int) $this->input('menu_item_id');
        $qty = max(0, (int) $this->input('qty', 1));
        $note = trim((string) $this->input('note', ''));

        // Nếu chưa có order_id nhưng có table_id, thử tìm hoặc tạo mới
        if ($orderId <= 0 && $tableId > 0) {
            $existingOrder = $this->orderModel->getOpenByTable($tableId);
            if ($existingOrder) {
                $orderId = (int) $existingOrder['id'];
            } else {
                // Tự động mở bàn và tạo order mới cho nhân viên
                $this->tableModel->open($tableId);
                $waiterId = Auth::isLoggedIn() ? Auth::user()['id'] : null;
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'waiter_id' => $waiterId,
                    'guest_count' => 1,
                    'shift_id' => $_SESSION['user_shift_id'] ?? null
                ]);
            }
        }

        if ($menuItemId > 0 && $orderId > 0) {
            $item = $this->menuModel->findById($menuItemId);
            if (!$item || !$item['is_available']) {
                $this->json(['ok' => false, 'message' => 'Món không khả dụng.'], 400);
            }

            $order = $this->orderModel->findById($orderId);
            if (!$order || $order['status'] !== 'open') {
                $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
            }

            if ($qty > 0) {
                $this->orderModel->addItem(
                    $orderId,
                    [
                        'menu_item_id' => $menuItemId,
                        'item_name' => $item['name'],
                        'item_price' => $item['price'],
                        'quantity' => $qty,
                        'note' => $note,
                        'status' => 'draft'
                    ]
                );
                
                // Log activity
                $this->activityLog->log(
                    ActivityLog::ACTION_CREATE,
                    'order_item',
                    null,
                    [
                        'order_id' => $orderId,
                        'menu_item_id' => $menuItemId,
                        'item_name' => $item['name'],
                        'quantity' => $qty,
                        'note' => $note
                    ],
                    ActivityLog::LEVEL_INFO
                );
            }
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        $this->formatItemsForJs($items);

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/add-set — Thêm set/combo vào order */
    public function addSet(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');
        $tableId = (int) $this->input('table_id');
        $setId = (int) $this->input('set_id');
        $items = $this->input('items', []);

        // Nếu chưa có order_id nhưng có table_id, thử tìm hoặc tạo mới
        if ($orderId <= 0 && $tableId > 0) {
            $existingOrder = $this->orderModel->getOpenByTable($tableId);
            if ($existingOrder) {
                $orderId = (int) $existingOrder['id'];
            } else {
                $this->tableModel->open($tableId);
                $waiterId = Auth::isLoggedIn() ? Auth::user()['id'] : null;
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'waiter_id' => $waiterId,
                    'guest_count' => 1,
                    'shift_id' => $_SESSION['user_shift_id'] ?? null
                ]);
            }
        }

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['status'] !== 'open') {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
        }

        $set = $this->setModel->findById($setId);
        if (!$set || !$set['is_active']) {
            $this->json(['ok' => false, 'message' => 'Set không tồn tại hoặc không khả dụng.'], 400);
        }

        // Add each item from the set
        foreach ($items as $itemData) {
            $menuItemId = (int) ($itemData['menu_item_id'] ?? 0);
            $qty = max(1, (int) ($itemData['quantity'] ?? 1));

            $menuItem = $this->menuModel->findById($menuItemId);
            if ($menuItem) {
                $this->orderModel->addItem(
                    $orderId,
                    [
                        'menu_item_id' => $menuItemId,
                        'item_name' => $menuItem['name'],
                        'item_price' => $menuItem['price'],
                        'quantity' => $qty,
                        'note' => "Set: {$set['name']}",
                        'status' => 'draft'
                    ]
                );
            }
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        $this->formatItemsForJs($items);

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/update — Cập nhật số lượng món */
    public function updateItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $qtyInput = $this->input('qty');

        $qty = 0;
        if (strpos((string) $qtyInput, 'delta:') === 0) {
            $delta = (int) str_replace('delta:', '', (string) $qtyInput);
            // Get current qty
            $db = getDB();
            $stmt = $db->prepare("SELECT quantity FROM order_items WHERE id = ?");
            $stmt->execute([$itemId]);
            $current = $stmt->fetchColumn();
            $qty = max(0, $current + $delta);
        } else {
            $qty = max(0, (int) $qtyInput);
        }

        $this->orderModel->updateItem($itemId, $qty);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        $this->formatItemsForJs($items);

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/remove — Xóa món khỏi order */
    public function removeItem(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');

        $this->orderModel->removeItem($itemId);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        $this->formatItemsForJs($items);

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
            'item_count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    /** POST /orders/update-note — Cập nhật ghi chú từng món */
    public function updateItemNote(): void
    {
        // Cả nhân viên lẫn khách QR (unauthenticated) đều có thể ghi chú
        $itemId  = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $note    = trim((string) $this->input('note', ''));

        $db = getDB();
        $db->prepare("UPDATE order_items SET note = ? WHERE id = ? AND order_id = ?")
           ->execute([$note, $itemId, $orderId]);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['price_fmt']    = formatPrice($it['item_price']);
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok'        => true,
            'total'     => $total,
            'total_fmt' => formatPrice($total),
            'items'     => $items,
        ]);
    }

    /** POST /orders/confirm — Xác nhận món (Xác nhận đặt món) */
    public function confirmOrder(): void
    {
        $orderId = (int) $this->input('order_id');
        $order = $this->orderModel->findById($orderId);

        if (!$order || $order['status'] !== 'open') {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ.'], 400);
        }

        if (Auth::isLoggedIn()) {
            // Nhân viên xác nhận: Chuyển thẳng sang confirmed
            $this->orderModel->confirmDraftItems($orderId);
            $message = 'Đã xác nhận món thành công!';
        } else {
            // Khách hàng gửi: Chuyển sang pending và tạo yêu cầu support
            $this->orderModel->confirmItemsToPending($orderId);

            // Tạo thông báo cho phục vụ
            require_once BASE_PATH . '/models/Support.php';
            $supportModel = new Support();
            $supportModel->createRequest($order['table_id'], 'new_order');

            $message = 'Đã gửi yêu cầu gọi món! Vui lòng chờ nhân viên xác nhận.';
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);

        // Format items for JS
        $this->formatItemsForJs($items);

        $this->json([
            'ok' => true,
            'message' => $message,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    /** GET /orders/print — In hóa đơn */
    public function print(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);
        $orderId = (int) $this->input('order_id');

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            die('Không tìm thấy hóa đơn.');
        }

        $table = $this->tableModel->findById($order['table_id']);
        $tableDisplayName = $this->tableModel->getFullDisplayName($order['table_id']);
        $items = $this->orderModel->getItems($orderId);
        $total = $this->orderModel->getTotal($orderId);

        // Fallback for payment_method from query string (immediate print)
        if ($this->input('payment_method')) {
            $order['payment_method'] = $this->input('payment_method');
        }

        // Tính subtotal và VAT (8%)
        $vatRate = 0.08;
        $subtotal = $total / (1 + $vatRate);
        $vat = $total - $subtotal;

        // Hiển thị view in không qua layout chung
        require_once BASE_PATH . '/views/orders/print.php';
    }
    /** GET /orders/history — Lịch sử bán hàng cho nhân viên */
    public function history(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $filterType = $this->input('filter_type', 'date'); // date, week, month
        $date = $this->input('date', date('Y-m-d'));
        $month = $this->input('month', date('n'));
        $year = $this->input('year', date('Y'));
        $week = $this->input('week', date('W'));
        $page = max(1, (int) $this->input('page', 1));
        $limit = 6; // User requested 6 items per page
        $offset = ($page - 1) * $limit;

        $filters = [];
        if ($filterType === 'date') {
            $filters['date'] = $date;
        } elseif ($filterType === 'month') {
            $filters['month'] = $month;
            $filters['year'] = $year;
        } elseif ($filterType === 'week') {
            $filters['week'] = $week;
            $filters['year'] = $year;
        }
        
        // Add pagination filters
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;

        $orders = $this->orderModel->getSalesHistory($filters);

        // Get total count and total revenue for pagination and summary
        unset($filters['limit']);
        unset($filters['offset']);
        $stats = $this->orderModel->getSalesHistoryStats($filters);
        $totalCount = $stats['count'] ?? 0;
        $totalRevenue = $stats['revenue'] ?? 0;
        $totalPages = ceil($totalCount / $limit);

        $this->view('layouts/waiter', [
            'view' => 'orders/history',
            'pageTitle' => 'Lịch sử Bán hàng',
            'orders' => $orders,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalCount' => $totalCount,
            'totalRevenue' => $totalRevenue,
            'filters' => [
                'type' => $filterType,
                'date' => $date,
                'month' => $month,
                'year' => $year,
                'week' => $week
            ]
        ]);
    }

    /** GET /orders/get-detail/{id} — Chi tiết order (AJAX) */
    public function getOrderDetail(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $orderId = (int) $this->input('id');
        if ($orderId <= 0) {
            $this->json(['ok' => false, 'message' => 'Order không hợp lệ']);
            return;
        }

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            $this->json(['ok' => false, 'message' => 'Order không tồn tại']);
            return;
        }

        $total = $this->orderModel->getTotal($orderId);
        $order['total_fmt'] = formatPrice($total);

        $items = $this->orderModel->getItems($orderId);

        // Format prices for JSON
        foreach ($items as &$item) {
            $item['price_fmt'] = formatPrice($item['item_price']);
            $item['subtotal_fmt'] = formatPrice($item['item_price'] * $item['quantity']);
        }

        $this->json([
            'ok' => true,
            'order' => $order,
            'items' => $items,
        ]);
    }
}
