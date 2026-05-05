<?php
// ============================================================
// MenuController — Waiter: View Digital Menu
// ============================================================

require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/MenuSet.php';

class MenuController extends Controller
{
    /** GET /menu — Xem menu (phục vụ & khách hàng) */
    public function index(): void
    {
        $tableIdFromUrl = (int) $this->input('table_id');
        $menuType = $this->input('type', 'asia');

        // Nếu URL không có table_id, xóa session cũ để nhân viên chọn lại bàn mới
        if ($tableIdFromUrl <= 0 && Auth::isLoggedIn()) {
            unset($_SESSION['customer_table_id']);
        }

        // LOGIC BẢO MẬT & THÔNG BÁO
        if ($tableIdFromUrl > 0) {
            // Trường hợp khách quét QR
            $_SESSION['customer_table_id'] = $tableIdFromUrl;
            
            // Gửi thông báo cho Waiter: Khách vừa vào bàn
            if (!isset($_SESSION['qr_scanned_notified'])) {
                require_once BASE_PATH . '/models/Support.php';
                $supportModel = new Support();
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                // Tạo thông báo loại 'scan_qr'
                $supportModel->createRequest($tableIdFromUrl, 'scan_qr');
                $_SESSION['qr_scanned_notified'] = true;
            }
        } else if (!isset($_SESSION['customer_table_id'])) {
            // Không có table_id và cũng không có trong session -> Phải là nhân viên
            Auth::requireRole(ROLE_WAITER, ROLE_ADMIN, ROLE_IT);
        }

        $itemModel = new MenuItem();
        $categoryModel = new MenuCategory();
        $setModel = new MenuSet();

        require_once BASE_PATH . '/models/Order.php';
        require_once BASE_PATH . '/models/Table.php';
        $tableModel = new Table();
        $orderModel = new Order();
        $allTables = $tableModel->getAll();

        // Ưu tiên lấy table_id từ session khách nếu có
        $tableId = $tableIdFromUrl ?: ($_SESSION['customer_table_id'] ?? 0);
        $orderId = (int) ($this->input('order_id') ?? 0);

        // Lấy thông tin bàn để lọc theo service_type
        $tableInfo = $tableId ? $tableModel->findById($tableId) : null;
        $serviceType = ''; // Bỏ lọc service_type để gộp menu phòng và nhà hàng

        // Lấy categories dựa trên món thực tế thuộc menu_type (không phải dựa trên c.menu_type)
        // Nếu là tab 'sets', chúng ta không cần lấy categories từ menu_items
        $categories = ($menuType === 'sets') ? [] : $categoryModel->getActiveByItemType($menuType);
        $grouped = ($menuType === 'sets') ? [] : $itemModel->getGroupedByCategory($menuType, $serviceType);

        // Lấy sets nếu là tab sets
        $sets = [];
        if ($menuType === 'sets') {
            $sets = $setModel->getActive();
            foreach ($sets as &$set) {
                $set['items'] = $setModel->getSetItems($set['id']);
            }
        }

        // LOGIC TỰ ĐỘNG CHO KHÁCH: Nếu là khách (không có orderId nhưng có tableId)
        if ($tableId > 0 && $orderId === 0) {
            // Xem bàn này có order nào đang 'open' không
            $existingOrder = $orderModel->findOpenOrderByTable($tableId);
            if ($existingOrder) {
                $orderId = (int) $existingOrder['id'];
            } else if (!Auth::isLoggedIn()) {
                // Nếu chưa có và là khách truy cập -> Tự động mở bàn/tạo order nháp
                $tableModel->open($tableId);
                 $orderId = $orderModel->create([
                    'table_id' => $tableId,
                    'guest_count' => 1,
                    'order_source' => 'qr_customer'
                ]);
            }
        }

        $orderItems = [];
        $orderTotal = 0;
        $order = null;

        if ($orderId > 0) {
            $order = $orderModel->findById($orderId);
            $orderItems = $orderModel->getItems($orderId);
            $totalInfo = $orderModel->getTotal($orderId);
            $orderTotal = is_array($totalInfo) ? ($totalInfo['total'] ?? 0) : $totalInfo;
        }

        // Chọn layout: Nhân viên (waiter) hoặc Khách hàng (public)
        $layout = Auth::isLoggedIn() ? 'layouts/waiter' : 'layouts/public';

        $this->view($layout, [
            'view' => 'menu/index',
            'pageTitle' => $tableId > 0 ? "Bàn {$tableId} - Gọi Món" : 'Menu',
            'categories' => $categories,
            'grouped' => $grouped,
            'sets' => $sets,
            'currentType' => $menuType,
            'menuTypes' => [
                ['key' => 'asia', 'label' => 'Món Á', 'icon' => 'fa-bowl-rice'],
                ['key' => 'europe', 'label' => 'Món Âu', 'icon' => 'fa-bread-slice'],
                ['key' => 'alacarte', 'label' => 'Ala Carte', 'icon' => 'fa-utensils'],
                ['key' => 'sets', 'label' => 'Set & Combo', 'icon' => 'fa-boxes-stacked'],
                ['key' => 'other', 'label' => 'Đồ uống / Khác', 'icon' => 'fa-glass-water'],
            ],
            'tableId' => $tableId,
            'orderId' => $orderId,
            'order' => $order,
            'orderItems' => $orderItems,
            'orderTotal' => $orderTotal,
            'tables' => $allTables,
            'isCustomer' => !Auth::isLoggedIn(),
            'tableModel' => $tableModel,
        ]);
    }
}
