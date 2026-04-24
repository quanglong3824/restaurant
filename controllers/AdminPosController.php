<?php
// ============================================================
// AdminPosController — Unified Admin POS Dashboard
// ============================================================

require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/MenuSet.php';
require_once BASE_PATH . '/models/OrderNotification.php';

class AdminPosController extends Controller
{
    private Table $tableModel;
    private Order $orderModel;
    private MenuItem $menuModel;
    private MenuCategory $categoryModel;
    private MenuSet $setModel;
    private OrderNotification $notifModel;

    public function __construct()
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $this->tableModel = new Table();
        $this->orderModel = new Order();
        $this->menuModel = new MenuItem();
        $this->categoryModel = new MenuCategory();
        $this->setModel = new MenuSet();
        $this->notifModel = new OrderNotification();
    }

    public function index(): void
    {
        $this->tableModel->syncStatuses();

        $type = $this->input('type', 'table');
        $tab = $this->input('tab', 'floor');
        $menuType = $this->input('menu_type', 'asia');
        $tableId = (int) $this->input('table_id', 0);
        $orderId = (int) $this->input('order_id', 0);

        $grouped = $this->tableModel->getAllGroupedByArea($type);
        $counts = $this->tableModel->countByStatus();
        $allTables = $this->tableModel->getAll();

        $categories = $this->categoryModel->getActiveByItemType($menuType);
        $groupedMenu = $this->menuModel->getGroupedByCategory($menuType);
        $sets = $this->setModel->getActive();
        foreach ($sets as &$set) {
            $set['items'] = $this->setModel->getSetItems($set['id']);
        }

        $realtimeOrders = $this->orderModel->getRealtimeOrders();
        foreach ($realtimeOrders as &$order) {
            $order['items'] = $this->orderModel->getItems($order['id']);
            $order['full_name'] = $this->tableModel->getFullDisplayName($order['table_id']);
            $order['total_fmt'] = formatPrice($order['total']);
            $order['opened_at_fmt'] = date('H:i', strtotime($order['opened_at']));
        }

        $order = null;
        $orderItems = [];
        $orderTotal = 0;
        if ($orderId > 0) {
            $order = $this->orderModel->findById($orderId);
            $orderItems = $this->orderModel->getItems($orderId);
            $orderTotal = $this->orderModel->getTotal($orderId);
        } elseif ($tableId > 0) {
            $order = $this->orderModel->findOpenOrderByTable($tableId);
            if ($order) {
                $orderId = (int) $order['id'];
                $orderItems = $this->orderModel->getItems($orderId);
                $orderTotal = $this->orderModel->getTotal($orderId);
            }
        }

        $notifications = $this->notifModel->getPaged(1, 20);
        $notifStats = [
            'unread' => $this->notifModel->countUnread(),
            'payment' => $this->notifModel->countUnreadByType('payment_request'),
            'order' => $this->notifModel->countUnreadByType('new_order'),
            'support' => $this->notifModel->countUnreadByType('support_request'),
        ];

        $menuItems = $this->menuModel->getAllActive();

        $table = $tableId > 0 ? $this->tableModel->findById($tableId) : null;

        $this->view('layouts/admin', [
            'view' => 'admin/pos/index',
            'pageTitle' => 'POS Dashboard',
            'pageSubtitle' => 'Quản lý tổng hợp bàn, menu, order',
            'tab' => $tab,
            'type' => $type,
            'menuType' => $menuType,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'grouped' => $grouped,
            'counts' => $counts,
            'allTables' => $allTables,
            'categories' => $categories,
            'groupedMenu' => $groupedMenu,
            'sets' => $sets,
            'realtimeOrders' => $realtimeOrders,
            'order' => $order,
            'orderItems' => $orderItems,
            'orderTotal' => $orderTotal,
            'table' => $table,
            'notifications' => $notifications,
            'notifStats' => $notifStats,
            'menuItems' => $menuItems,
            'tableModel' => $this->tableModel,
        ]);
    }

    public function floorData(): void
    {
        $type = $this->input('type', 'table');
        $this->tableModel->syncStatuses();
        $grouped = $this->tableModel->getAllGroupedByArea($type);
        $counts = $this->tableModel->countByStatus();

        $this->json([
            'ok' => true,
            'grouped' => $grouped,
            'counts' => $counts,
        ]);
    }

    public function menuData(): void
    {
        $menuType = $this->input('type', 'asia');
        $serviceType = $this->input('service', 'restaurant');

        $categories = $this->categoryModel->getActiveByItemType($menuType);
        $grouped = $this->menuModel->getGroupedByCategory($menuType, $serviceType);
        $sets = [];
        if ($menuType === 'sets') {
            $sets = $this->setModel->getActive();
            foreach ($sets as &$set) {
                $set['items'] = $this->setModel->getSetItems($set['id']);
            }
        }

        foreach ($grouped as $catName => &$items) {
            foreach ($items as &$item) {
                $item['price_fmt'] = formatPrice($item['price']);
            }
        }

        $this->json([
            'ok' => true,
            'categories' => $categories,
            'grouped' => $grouped,
            'sets' => $sets,
        ]);
    }

    public function orderData(): void
    {
        $orderId = (int) $this->input('order_id', 0);
        $tableId = (int) $this->input('table_id', 0);

        if ($orderId <= 0 && $tableId > 0) {
            $order = $this->orderModel->findOpenOrderByTable($tableId);
            if ($order) $orderId = (int) $order['id'];
        }

        if ($orderId <= 0) {
            $this->json(['ok' => false, 'message' => 'Không có order']);
            return;
        }

        $order = $this->orderModel->findById($orderId);
        $items = $this->orderModel->getItems($orderId);
        $total = $this->orderModel->getTotal($orderId);
        $table = $this->tableModel->findById($order['table_id']);

        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'order' => $order,
            'order_id' => $orderId,
            'items' => $items,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'table' => $table,
            'table_name' => $this->tableModel->getFullDisplayName($order['table_id']),
        ]);
    }

    public function realtimeData(): void
    {
        $this->tableModel->syncStatuses();
        $orders = $this->orderModel->getRealtimeOrders();

        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getItems($order['id']);
            $order['full_name'] = $this->tableModel->getFullDisplayName($order['table_id']);
            $order['total_fmt'] = formatPrice($order['total']);
            $order['opened_at_fmt'] = date('H:i', strtotime($order['opened_at']));
            $order['is_idle'] = empty($order['items']);
            $order['idle_seconds'] = $order['is_idle'] ? (time() - strtotime($order['opened_at'])) : 0;

            foreach ($order['items'] as &$it) {
                $it['item_name'] = $it['item_name'] ?? $it['name'] ?? '';
                $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
            }
        }

        $this->json([
            'ok' => true,
            'data' => $orders,
            'counts' => $this->tableModel->countByStatus(),
        ]);
    }

    public function notifData(): void
    {
        $page = max(1, (int) $this->input('page', 1));
        $notifications = $this->notifModel->getPaged($page, 20);
        $stats = [
            'unread' => $this->notifModel->countUnread(),
            'payment' => $this->notifModel->countUnreadByType('payment_request'),
            'order' => $this->notifModel->countUnreadByType('new_order'),
            'support' => $this->notifModel->countUnreadByType('support_request'),
        ];

        $this->json([
            'ok' => true,
            'notifications' => $notifications,
            'stats' => $stats,
        ]);
    }

    public function openTable(): void
    {
        $tableId = (int) $this->input('table_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));

        $table = $this->tableModel->findById($tableId);
        if (!$table || $table['status'] === 'occupied') {
            $this->json(['ok' => false, 'message' => 'Bàn không hợp lệ hoặc đã có khách.'], 400);
        }

        $this->tableModel->open($tableId);
        $orderId = $this->orderModel->create([
            'table_id' => $tableId,
            'waiter_id' => Auth::user()['id'],
            'guest_count' => $guestCount,
            'shift_id' => $_SESSION['user_shift_id'] ?? null
        ]);

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'table_id' => $tableId,
            'message' => 'Đã mở bàn thành công.',
        ]);
    }

    public function addItem(): void
    {
        $orderId = (int) $this->input('order_id');
        $tableId = (int) $this->input('table_id');
        $menuItemId = (int) $this->input('menu_item_id');
        $qty = max(1, (int) $this->input('qty', 1));
        $note = trim((string) $this->input('note', ''));

        if ($orderId <= 0 && $tableId > 0) {
            $existingOrder = $this->orderModel->findOpenOrderByTable($tableId);
            if ($existingOrder) {
                $orderId = (int) $existingOrder['id'];
            } else {
                $this->tableModel->open($tableId);
                $orderId = $this->orderModel->create([
                    'table_id' => $tableId,
                    'waiter_id' => Auth::user()['id'],
                    'guest_count' => 1,
                    'shift_id' => $_SESSION['user_shift_id'] ?? null
                ]);
            }
        }

        if ($menuItemId > 0 && $orderId > 0) {
            $item = $this->menuModel->findById($menuItemId);
            if ($item && $item['is_available']) {
                $this->orderModel->addItem($orderId, [
                    'menu_item_id' => $menuItemId,
                    'item_name' => $item['name'],
                    'item_price' => $item['price'],
                    'quantity' => $qty,
                    'note' => $note,
                    'status' => 'draft'
                ]);
            }
        }

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'order_id' => $orderId,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    public function updateItemQty(): void
    {
        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $delta = (int) $this->input('delta', 0);

        $db = getDB();
        $stmt = $db->prepare("SELECT quantity FROM order_items WHERE id = ?");
        $stmt->execute([$itemId]);
        $current = (int) $stmt->fetchColumn();
        $newQty = max(0, $current + $delta);

        $this->orderModel->updateItem($itemId, $newQty);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    public function removeItem(): void
    {
        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');

        $this->orderModel->removeItem($itemId);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    public function confirmOrder(): void
    {
        $orderId = (int) $this->input('order_id');
        $this->orderModel->confirmDraftItems($orderId);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'message' => 'Đã xác nhận món.',
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    public function payment(): void
    {
        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');
        $paymentMethod = $this->input('payment_method', 'cash');

        $total = $this->orderModel->getTotal($orderId);

        if ($total == 0) {
            $this->orderModel->cancel($orderId);
            $this->tableModel->close($tableId);
            $this->json(['ok' => true, 'message' => 'Đã hủy bàn (chưa có món).']);
            return;
        }

        $this->orderModel->close($orderId, $paymentMethod);
        $this->tableModel->close($tableId);

        $this->json(['ok' => true, 'message' => 'Thanh toán thành công.']);
    }

    public function updateGuest(): void
    {
        $orderId = (int) $this->input('order_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));
        $this->orderModel->updateGuestCount($orderId, $guestCount);
        $this->json(['ok' => true]);
    }

    public function transferTable(): void
    {
        $fromId = (int) $this->input('from_table_id');
        $toId = (int) $this->input('to_table_id');

        if ($fromId === $toId) {
            $this->json(['ok' => false, 'message' => 'Cùng một bàn']);
            return;
        }

        $toTable = $this->tableModel->findById($toId);
        if (!$toTable || $toTable['status'] === 'occupied') {
            $this->json(['ok' => false, 'message' => 'Bàn đích không hợp lệ']);
            return;
        }

        $order = $this->orderModel->findOpenOrderByTable($fromId);
        if ($order) {
            $db = getDB();
            $db->prepare("UPDATE orders SET table_id = ? WHERE id = ?")->execute([$toId, $order['id']]);
            $this->tableModel->close($fromId);
            $this->tableModel->open($toId);
            $this->json(['ok' => true, 'message' => 'Chuyển bàn thành công', 'new_table_id' => $toId]);
        } else {
            $this->json(['ok' => false, 'message' => 'Bàn không có order']);
        }
    }

    public function mergeTable(): void
    {
        $childId = (int) $this->input('child_id');
        $parentId = (int) $this->input('parent_id');

        if ($childId === $parentId) {
            $this->json(['ok' => false, 'message' => 'Không thể ghép cùng bàn']);
            return;
        }

        $ok = $this->tableModel->mergeTable($childId, $parentId);
        $this->json([
            'ok' => $ok,
            'message' => $ok ? 'Đã ghép bàn' : 'Không thể ghép'
        ]);
    }

    public function unmergeTable(): void
    {
        $tableId = (int) $this->input('table_id');
        $this->tableModel->unmergeTable($tableId);
        $this->tableModel->syncStatuses();
        $this->json(['ok' => true, 'message' => 'Đã tách bàn']);
    }

    public function splitOrder(): void
    {
        $orderId = (int) $this->input('order_id');
        $targetTableId = (int) $this->input('target_table_id');
        $guestCount = (int) $this->input('guest_count', 2);
        $itemIds = $this->input('item_ids', []);

        if (!is_array($itemIds) || empty($itemIds)) {
            $this->json(['ok' => false, 'message' => 'Chọn món cần tách']);
            return;
        }

        $itemIds = array_map('intval', $itemIds);

        try {
            if ($targetTableId > 0) {
                $this->tableModel->unmergeTable($targetTableId);
            }

            $result = $this->orderModel->splitItems($orderId, $itemIds, $targetTableId, null, $guestCount);

            if ($result['ok']) {
                $this->tableModel->open($targetTableId);
                $this->tableModel->syncStatuses();
                $this->json(['ok' => true, 'message' => $result['message'], 'new_order_id' => $result['new_order_id']]);
            } else {
                $this->json(['ok' => false, 'message' => $result['message']]);
            }
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateItemNote(): void
    {
        $itemId = (int) $this->input('item_id');
        $orderId = (int) $this->input('order_id');
        $note = trim((string) $this->input('note', ''));

        $db = getDB();
        $db->prepare("UPDATE order_items SET note = ? WHERE id = ? AND order_id = ?")
           ->execute([$note, $itemId, $orderId]);

        $total = $this->orderModel->getTotal($orderId);
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as &$it) {
            $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
        }

        $this->json([
            'ok' => true,
            'total' => $total,
            'total_fmt' => formatPrice($total),
            'items' => $items,
        ]);
    }

    public function markNotifRead(): void
    {
        $id = (int) $this->input('id', 0);
        $userId = Auth::user()['id'];

        if ($id > 0) {
            $this->notifModel->markAsRead($id, $userId);
        } else {
            $this->notifModel->markAllAsRead($userId);
        }

        $this->json(['ok' => true]);
    }
}