<?php
// ============================================================
// AdminTableController — Full CRUD + Toggle Active
// ============================================================

require_once BASE_PATH . '/models/Table.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AdminTableController extends Controller
{
    private Table $model;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->model = new Table();
        $this->activityLog = new ActivityLog();
    }

    /** GET /admin/tables */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';

        // Tự động đồng bộ trạng thái bàn
        $this->model->syncStatuses();

        require_once BASE_PATH . '/models/QrTable.php';
        $qrModel = new QrTable();
        $qrModel->cleanupInvalidTokens();

        // Lấy tất cả bàn/phòng và nhóm theo khu vực (floor/area)
        $allTables = $this->model->getAllForAdminByType($type);
        
        // Group by area (floor)
        $groupedTables = [];
        foreach ($allTables as $t) {
            $area = $t['area'] ?: 'Chưa phân khu';
            if (!isset($groupedTables[$area])) {
                $groupedTables[$area] = [];
            }
            $groupedTables[$area][] = $t;
        }
        
        $this->view('layouts/admin', [
            'view' => 'admin/tables/index',
            'pageTitle' => $type === 'room' ? 'Quản lý Phòng Lưu Trú' : 'Quản lý Bàn',
            'pageSubtitle' => count($allTables) . ($type === 'room' ? ' phòng' : ' bàn'),
            'tables' => $allTables,
            'groupedTables' => $groupedTables,
            'type' => $type,
            'editItem' => null,
        ]);
    }

    /** POST /admin/tables/store */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $name = trim((string) $this->input('name', ''));
        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';

        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên không được để trống.'];
            $this->redirect('/admin/tables?type=' . $type);
        }

        $data = [
            'name' => $name,
            'area' => trim((string) $this->input('area', '')) ?: null,
            'capacity' => max(1, (int) $this->input('capacity', 4)),
            'type' => $type,
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        
        $tableId = $this->model->create($data);

        // Auto-generate QR code for the new table
        require_once BASE_PATH . '/models/QrTable.php';
        $qrModel = new QrTable();
        $token = bin2hex(random_bytes(8));
        $qrModel->generate($tableId, $token);

        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_CREATE,
            'table',
            $tableId,
            $data,
            ActivityLog::LEVEL_INFO
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm ' . ($type === 'room' ? 'phòng' : 'bàn') . ' và tạo mã QR!'];
        $this->redirect('/admin/tables?type=' . $type);
    }

    /** GET /admin/tables/edit?id= */
    public function edit(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không tìm thấy.'];
            $this->redirect('/admin/tables');
        }

        $type = $item['type'] ?? 'table';
        $tables = $this->model->getAllForAdminByType($type);
        $this->view('layouts/admin', [
            'view' => 'admin/tables/index',
            'pageTitle' => $type === 'room' ? 'Quản lý Phòng Lưu Trú' : 'Quản lý Bàn',
            'pageSubtitle' => count($tables) . ($type === 'room' ? ' phòng' : ' bàn'),
            'tables' => $tables,
            'type' => $type,
            'editItem' => $item,
        ]);
    }

    /** POST /admin/tables/update */
    public function update(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $name = trim((string) $this->input('name', ''));
        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';

        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên không được để trống.'];
            $this->redirect('/admin/tables?type=' . $type);
        }

        $data = [
            'name' => $name,
            'area' => trim((string) $this->input('area', '')) ?: null,
            'capacity' => max(1, (int) $this->input('capacity', 4)),
            'type' => $type,
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => (int) $this->input('is_active', 1),
        ];
        
        $this->model->update($id, $data);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'table',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật!'];
        $this->redirect('/admin/tables?type=' . $type);
    }

    /** POST /admin/tables/delete */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->model->findById($id);
        $type = $item['type'] ?? 'table';
        
        $ok = $this->model->delete($id);

        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Đang có khách, không thể xóa.'];
            
            // Log failed delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'table',
                $id,
                ['reason' => 'has_order', 'name' => $item['name'] ?? 'unknown'],
                ActivityLog::LEVEL_WARNING
            );
        } else {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa.'];
            
            // Log successful delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'table',
                $id,
                ['name' => $item['name'] ?? 'unknown', 'type' => $type],
                ActivityLog::LEVEL_INFO
            );
        }
        $this->redirect('/admin/tables?type=' . $type);
    }

    /** GET /admin/tables/qr-instructions */
    public function qrInstructions(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $this->view('layouts/admin', [
            'view' => 'admin/tables/qr_instructions',
            'pageTitle' => 'Hướng dẫn đặt món QR',
            'pageSubtitle' => 'In hướng dẫn sử dụng QR code cho khách hàng',
        ]);
    }
}
