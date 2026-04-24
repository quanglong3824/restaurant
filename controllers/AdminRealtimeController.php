<?php
// ============================================================
// AdminRealtimeController — Real-time Monitoring
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/models/Table.php';

class AdminRealtimeController extends Controller
{
    private Order $orderModel;
    private Table $tableModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->tableModel = new Table();
    }

    /**
     * GET /admin/realtime — Dashboard theo thời gian thực
     */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        try {
            // Đồng bộ trạng thái bàn
            $this->tableModel->syncStatuses();

            // Lấy tất cả bàn đang bận và chi tiết order
            $rawOrders = $this->orderModel->getRealtimeOrders();
            $orders = [];

            foreach ($rawOrders as $order) {
                $order['items'] = $this->orderModel->getItems($order['id']);
                $order['full_name'] = $this->tableModel->getFullDisplayName($order['table_id']);
                $order['rounds'] = $this->calculateOrderRounds($order['items']);
                $orders[] = $order;
            }

            // Lấy bàn trống để modal mở bàn
            $availableTables = $this->tableModel->getAvailable();

            // Lấy menu items để modal thêm món
            require_once BASE_PATH . '/models/MenuItem.php';
            $menuModel = new MenuItem();
            $menuItems = $menuModel->getAllActive();

            $this->view('layouts/admin', [
                'view' => 'admin/realtime/index',
                'pageTitle' => 'Quản lý Thời gian thực',
                'pageSubtitle' => 'Theo dõi tình trạng các bàn đang phục vụ',
                'orders' => $orders,
                'counts' => $this->tableModel->countByStatus(),
                'availableTables' => $availableTables,
                'menuItems' => $menuItems,
            ]);
        } catch (Exception $e) {
            error_log("AdminRealtimeController::index error: " . $e->getMessage());
            $this->view('layouts/admin', [
                'view' => 'admin/realtime/index',
                'pageTitle' => 'Quản lý Thời gian thực',
                'pageSubtitle' => 'Lỗi: ' . $e->getMessage(),
                'orders' => [],
                'counts' => ['available' => 0, 'occupied' => 0],
                'availableTables' => [],
                'menuItems' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX để update thông tin không cần load lại trang
     */
    public function data(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        try {
            // Đồng bộ trạng thái bàn
            $this->tableModel->syncStatuses();

            $rawOrders = $this->orderModel->getRealtimeOrders();
            $orders = [];

            foreach ($rawOrders as $order) {
                $order['items'] = $this->orderModel->getItems($order['id']);
                $order['rounds'] = $this->calculateOrderRounds($order['items']);
                $order['full_name'] = $this->tableModel->getFullDisplayName($order['table_id']);
                $order['opened_at_fmt'] = date('H:i', strtotime($order['opened_at']));
                $order['closed_at_fmt'] = $order['closed_at'] ? date('H:i', strtotime($order['closed_at'])) : null;
                $order['total_fmt'] = formatPrice($order['total']);
                $order['waiter_name'] = $order['waiter_name'] ?? '';
                
                // Tính toán thời gian Idle (nếu chưa có món nào)
                $order['is_idle'] = empty($order['items']);
                $order['idle_seconds'] = $order['is_idle'] ? (time() - strtotime($order['opened_at'])) : 0;
                
                // Format items prices
                foreach ($order['items'] as &$it) {
                    $it['item_name'] = $it['item_name'] ?? $it['name'] ?? '';
                    $it['item_price_fmt'] = formatPrice($it['item_price']);
                    $it['subtotal_fmt'] = formatPrice($it['item_price'] * $it['quantity']);
                }
                $orders[] = $order;
            }
            
            $this->json([
                'ok' => true, 
                'data' => $orders,
                'counts' => $this->tableModel->countByStatus(),
                'debug' => [
                    'raw_count' => count($rawOrders),
                    'processed_count' => count($orders),
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
        } catch (Exception $e) {
            error_log("AdminRealtimeController::data error: " . $e->getMessage());
            $this->json([
                'ok' => false,
                'message' => $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * POST /admin/realtime/dismiss — Ẩn một card (lưu vào Database)
     */
    public function dismiss(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $orderId = (int) $this->input('order_id');
        if ($orderId > 0) {
            $this->orderModel->dismissFromRealtime($orderId);
        }
        $this->json(['ok' => true]);
    }

    /**
     * GET /admin/realtime/qr-sessions — Monitor all QR devices
     */
    public function qrSessions(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $sessions = $this->orderModel->getGroupedQrSessions();
        
        $this->view('layouts/admin', [
            'view' => 'admin/realtime/qr_sessions',
            'pageTitle' => 'Giám sát Phiên QR',
            'pageSubtitle' => 'Danh sách thiết bị khách đang quét nhiều bàn',
            'sessions' => $sessions
        ]);
    }

    private function calculateOrderRounds(array $items): int
    {
        if (empty($items))
            return 0;

        $rounds = 0;
        $lastTime = null;

        // Sắp xếp items theo thời gian tạo
        usort($items, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        foreach ($items as $it) {
            $currentTime = strtotime($it['created_at']);
            // Nếu cách nhau hơn 5 phút thì tính là đợt mới
            if ($lastTime === null || ($currentTime - $lastTime) > 300) {
                $rounds++;
            }
            $lastTime = $currentTime;
        }

        return $rounds;
    }
}
