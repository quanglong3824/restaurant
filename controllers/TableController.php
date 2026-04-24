<?php
// ============================================================
// TableController — Waiter: Table Map
// ============================================================

require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class TableController extends Controller
{
    private Table $tableModel;
    private Order $orderModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->tableModel = new Table();
        $this->orderModel = new Order();
        $this->activityLog = new ActivityLog();
    }

    /** GET /tables — Sơ đồ bàn */
    public function index(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);

        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';

        // Đồng bộ trạng thái bàn
        $this->tableModel->syncStatuses();

        $grouped = $this->tableModel->getAllGroupedByArea($type);
        $counts = $this->tableModel->countByStatus();

        $this->view('layouts/waiter', [
            'view' => 'tables/index',
            'pageTitle' => $type === 'room' ? 'Sơ đồ Phòng' : 'Sơ đồ Bàn',
            'grouped' => $grouped,
            'counts' => $counts,
            'type' => $type,
            'tableModel' => $this->tableModel,
        ]);
    }

    /** POST /tables/open — Mở bàn, tạo order */
    public function open(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $guestCount = max(1, (int) $this->input('guest_count', 1));
        $waiterId = Auth::user()['id'];
        $shiftId = $_SESSION['user_shift_id'] ?? null;

        $table = $this->tableModel->findById($tableId);
        if (!$table || $table['status'] === 'occupied') {
            // Check if AJAX request
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Bàn không hợp lệ hoặc đã có khách.'], 400);
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn không hợp lệ hoặc đã có khách.'];
            $this->redirect('/tables');
        }

        try {
            $this->tableModel->open($tableId);
            $orderId = $this->orderModel->create([
                'table_id' => $tableId,
                'waiter_id' => $waiterId,
                'guest_count' => $guestCount,
                'shift_id' => $shiftId
            ]);

            // Log activity
            $this->activityLog->log(
                ActivityLog::ACTION_CREATE,
                'order',
                $orderId,
                [
                    'table_id' => $tableId,
                    'waiter_id' => $waiterId,
                    'guest_count' => $guestCount,
                    'shift_id' => $shiftId
                ],
                ActivityLog::LEVEL_INFO
            );

            // Return JSON for AJAX or redirect for form submit
            if ($this->isAjax()) {
                $this->json([
                    'ok' => true,
                    'message' => 'Đã mở bàn thành công.',
                    'order_id' => $orderId,
                    'table_id' => $tableId
                ]);
            }
            $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Lỗi khi mở bàn: ' . $e->getMessage()], 500);
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi khi mở bàn: ' . $e->getMessage()];
            $this->redirect('/tables');
        }
    }

    /** POST /tables/merge — Ghép bàn */
    public function merge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('child_id');
        $parentId = (int) $this->input('parent_id');
        $redirectUrl = (string) $this->input('redirect', '/tables');

        if ($childId === $parentId) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể ghép bàn với chính nó.'];
            $this->redirect($redirectUrl);
        }

        $ok = $this->tableModel->mergeTable($childId, $parentId);
        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã ghép bàn thành công.'];
            
            // Log activity
            $this->activityLog->log(
                ActivityLog::ACTION_UPDATE,
                'table',
                $childId,
                ['action' => 'merge', 'parent_id' => $parentId],
                ActivityLog::LEVEL_NOTICE
            );
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn đang có khách, không thể ghép.'];
        }

        $this->redirect($redirectUrl);
    }

    /** POST /tables/unmerge — Hủy ghép bàn */
    public function unmerge(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $childId = (int) $this->input('table_id');
        $redirectUrl = (string) $this->input('redirect', '/tables');

        $childTable = $this->tableModel->findById($childId);
        $parentId = $childTable['parent_id'] ?? null;
        
        $this->tableModel->unmergeTable($childId);
        $this->tableModel->syncStatuses(); // Đồng bộ lại trạng thái dựa trên order

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tách bàn.'];
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'table',
            $childId,
            ['action' => 'unmerge', 'previous_parent_id' => $parentId],
            ActivityLog::LEVEL_NOTICE
        );
        
        $this->redirect($redirectUrl);
    }

    /** POST /tables/transfer — Chuyển bàn */
    public function transfer(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $fromTableId = (int) $this->input('from_table_id');
        $toTableId = (int) $this->input('to_table_id');

        if ($fromTableId === $toTableId) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Bạn đang chọn cùng một bàn.'];
            $this->redirect('/tables');
        }

        $toTable = $this->tableModel->findById($toTableId);
        if (!$toTable || $toTable['status'] === 'occupied' || $toTable['parent_id'] !== null) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn đích không hợp lệ hoặc đang có khách.'];
            $this->redirect('/tables');
        }

        $order = $this->orderModel->findOpenOrderByTable($fromTableId);
        if ($order) {
            // Update table_id in orders
            $db = getDB();
            $db->prepare("UPDATE orders SET table_id = ? WHERE id = ?")->execute([$toTableId, $order['id']]);

            // Cập nhật trạng thái bàn
            $this->tableModel->close($fromTableId); // Bàn cũ thành trống
            $this->tableModel->open($toTableId);    // Bàn mới thành bận

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Chuyển bàn thành công.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bàn cũ không có order nào để chuyển.'];
        }

        $this->redirect('/tables');
    }

    /** POST /tables/merge_areas — Ghép khu vực */
    public function merge_areas(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $areas = $_POST['areas'] ?? [];
        if (!is_array($areas) || count($areas) < 2) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn ít nhất 2 khu vực để ghép.'];
            $this->redirect('/tables');
        }

        $mainTableId = $this->tableModel->mergeAreas($areas);
        if ($mainTableId > 0) {
            // Check if there's already an open order on the main table
            $existingOrder = $this->orderModel->getOpenByTable($mainTableId);
            if (!$existingOrder) {
                // Tự động tạo một order mới với guest_count = 1 để bàn này có thể click vào và xem chi tiết
                $waiterId = Auth::user()['id'];
                $shiftId = $_SESSION['user_shift_id'] ?? null;
                $this->orderModel->create([
                    'table_id' => $mainTableId,
                    'waiter_id' => $waiterId,
                    'guest_count' => 1,
                    'shift_id' => $shiftId
                ]);
            }
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã ghép các khu vực thành công.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không có bàn khả dụng để ghép trong các khu này.'];
        }
        $this->redirect('/tables');
    }

    /** Tách các khu vực đoàn lớn */
    public function unmerge_areas()
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $areas = $_POST['unmerge_areas'] ?? [];
        if (!is_array($areas) || empty($areas)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn ít nhất 1 khu vực để tách.'];
            $this->redirect('/tables');
        }

        $this->tableModel->unmergeAreas($areas);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tách và làm trống các khu vực thành công.'];
        $this->redirect('/tables');
    }

    /** POST /tables/close — Đóng bàn */
    public function close(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');
        $paymentMethod = (string) $this->input('payment_method', 'cash');
        $redirectToOrder = $this->input('redirect_to_order') === '1';

        if (!in_array($paymentMethod, ['cash', 'transfer', 'card'])) {
            $paymentMethod = 'cash';
        }

        try {
            $totalInfo = $this->orderModel->getTotal($orderId);
            $total = is_array($totalInfo) ? ($totalInfo['total'] ?? 0) : $totalInfo;

            if ($total == 0) {
                if ($orderId > 0) {
                    $this->orderModel->cancel($orderId);
                }
                $this->tableModel->close($tableId);
                $message = 'Đã huỷ bàn thành công vì chưa gọi món.';
                $type = 'info';
            } else {
                $this->orderModel->close($orderId, $paymentMethod);
                $this->tableModel->close($tableId);
                $message = 'Đã đóng bàn và thanh toán thành công.';
                $type = 'success';
            }

            if ($this->isAjax()) {
                $this->json(['ok' => true, 'message' => $message]);
                return;
            }
            $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi đóng bàn: ' . $e->getMessage()];
        }

        // If the request comes from the order page with redirect_to_order flag, stay on the order page
        if ($redirectToOrder) {
            $this->redirect('/orders?table_id=' . $tableId . '&order_id=' . $orderId);
        } else {
            $this->redirect('/tables');
        }
    }
    
    /** GET /tables/getMergedChildren — Lấy danh sách bàn con đã ghép */
    public function getMergedChildren(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('id');
        $children = $this->tableModel->getMergedTables($tableId);

        $this->json([
            'ok' => true,
            'children' => $children,
        ]);
    }

    /**
     * POST /tables/split — Tách bàn với selected items
     * Expects: table_id (current merged table), order_id, item_ids[], target_table_id (new or existing)
     */
    public function split(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $tableId = (int) $this->input('table_id');
        $orderId = (int) $this->input('order_id');
        $targetTableId = (int) $this->input('target_table_id');
        $guestCount = (int) $this->input('guest_count');
        $itemIds = $this->input('item_ids', []);
        
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->json(['ok' => false, 'message' => 'Vui lòng chọn món cần tách']);
            return;
        }

        // Convert item_ids to integers
        $itemIds = array_map('intval', $itemIds);

        try {
            // 1. Tách bàn đích ra khỏi bất kỳ nhóm ghép nào trước (giữ nó độc lập)
            if ($targetTableId > 0) {
                $this->tableModel->unmergeTable($targetTableId);
            }

            // 2. Tách món và tạo order mới cho bàn đích
            $result = $this->orderModel->splitItems($orderId, $itemIds, $targetTableId, null, $guestCount);
            
            if ($result['ok']) {
                // 3. Đánh dấu bàn đích là bận và có order riêng
                $this->tableModel->open($targetTableId);
                
                // 4. Đồng bộ lại để tất cả bàn cùng cập nhật đúng trạng thái bận
                $this->tableModel->syncStatuses();
                
                $this->json([
                    'ok' => true, 
                    'message' => $result['message'],
                    'new_order_id' => $result['new_order_id']
                ]);
            } else {
                $this->json(['ok' => false, 'message' => $result['message']]);
            }
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * POST /tables/transfer-item — Chuyển món từ order này sang order khác
     * Expects: item_id, target_order_id, target_table_id
     */
    public function transfer_item(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $itemId = (int) $this->input('item_id');
        $targetOrderId = (int) $this->input('target_order_id');
        $targetTableId = (int) $this->input('target_table_id');

        if ($itemId <= 0 || $targetOrderId <= 0 || $targetTableId <= 0) {
            $this->json(['ok' => false, 'message' => 'Thông tin không hợp lệ']);
            return;
        }

        try {
            $result = $this->orderModel->transferItem($itemId, $targetOrderId, $targetTableId);
            $this->json($result);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET /tables/get-items-by-table — Lấy món theo bàn (cho merged tables)
     * Expects: order_id
     */
    public function get_items_by_table(): void
    {
        Auth::requireRole(ROLE_WAITER, ROLE_ADMIN);

        $orderId = (int) $this->input('order_id');

        try {
            $items = $this->orderModel->getItemsByTable($orderId);
            $this->json(['ok' => true, 'items' => $items]);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }


}