<?php
// ============================================================
// AdminQrController — Aurora Restaurant
// ============================================================

require_once BASE_PATH . '/models/QrTable.php';
require_once BASE_PATH . '/models/Table.php';

class AdminQrController extends Controller
{
    private QrTable $qrModel;
    private Table $tableModel;

    public function __construct()
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        $this->qrModel = new QrTable();
        $this->tableModel = new Table();
    }

    public function index(): void
    {
        // Auto-cleanup invalid QR tokens (historical garbage data)
        $this->qrModel->cleanupInvalidTokens();

        $qrCodes = $this->qrModel->getAllWithTableInfo();
        $tables = $this->tableModel->getAll();

        $this->view('layouts/admin', [
            'view' => 'admin/tables/qr_codes',
            'pageTitle' => 'Quản lý mã QR',
            'qrCodes' => $qrCodes,
            'tables' => $tables
        ]);
    }

    public function generate(): void
    {
        $tableId = (int)$_POST['table_id'];
        $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/qr-codes';
        
        if (!$tableId) {
            $this->redirect($referer);
            return;
        }

        // Generate a random unique token (shorter for faster QR scanning)
        $token = bin2hex(random_bytes(8));
        $this->qrModel->generate($tableId, $token);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã tạo mã QR mới thành công.'];
        $this->redirect($referer);
    }

    public function download(): void
    {
        // Implementation for downloading/printing QR codes
        // This could use the phpqrcode library
        $tableId = (int)$_GET['table_id'];
        $token = $_GET['token'];

        // Get table info
        $table = $this->tableModel->findById($tableId);
        $tableName = $table ? $table['name'] : $tableId;

        // Mark as printed
        $this->qrModel->markAsPrinted($tableId);

        // Redirect to a page that renders the QR code
        $this->view('layouts/admin', [
            'view' => 'admin/tables/qr_download',
            'pageTitle' => 'Tải mã QR',
            'tableId' => $tableId,
            'tableName' => $tableName,
            'token' => $token
        ]);
    }

    public function delete(): void
    {
        $id = (int)$_POST['id'];
        if ($id) {
            $this->qrModel->execute("DELETE FROM qr_tables WHERE id = ?", [$id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa mã QR thành công.'];
        }
        $this->redirect('/admin/qr-codes');
    }

    /**
     * GET /admin/qr-codes/print-bulk — Bulk print QR codes for tables/rooms
     */
    public function printBulk(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';
        
        // Get all tables with QR tokens
        $tables = $this->tableModel->getAllForAdminByType($type);
        
        // Filter tables that have QR tokens
        $tablesWithQr = [];
        foreach ($tables as $t) {
            if (!empty($t['qr_token'])) {
                $tablesWithQr[] = $t;
            }
        }
        
        $this->view('admin/tables/qr_print_bulk', [
            'type' => $type,
            'tables' => $tablesWithQr,
        ]);
    }

    /**
     * POST /admin/qr-codes/print-bulk — Bulk print QR codes for selected tables/rooms
     */
    public function printBulkPost(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $type = $this->input('type', 'table');
        if (!in_array($type, ['table', 'room'])) $type = 'table';
        
        $tablesJson = $this->input('tables', '[]');
        $selectedTables = json_decode($tablesJson, true);
        
        if (!is_array($selectedTables) || empty($selectedTables)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn ít nhất 1 bàn/phòng để in.'];
            $this->redirect('/admin/tables?type=' . $type);
            return;
        }
        
        // Filter to only include tables with valid tokens
        $tablesWithQr = [];
        foreach ($selectedTables as $t) {
            if (!empty($t['token'])) {
                $tablesWithQr[] = [
                    'id' => $t['id'],
                    'name' => $t['name'],
                    'qr_token' => $t['token'],
                ];
            }
        }
        
        $this->view('admin/tables/qr_print_bulk', [
            'type' => $type,
            'tables' => $tablesWithQr,
        ]);
    }
}
