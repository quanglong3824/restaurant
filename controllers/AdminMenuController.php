<?php
// ============================================================
// AdminMenuController — Admin: Manage Menu Items
// ============================================================

require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/MenuType.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AdminMenuController extends Controller
{
    private MenuItem $itemModel;
    private MenuCategory $categoryModel;
    private MenuType $typeModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->itemModel = new MenuItem();
        $this->categoryModel = new MenuCategory();
        $this->typeModel = new MenuType();
        $this->activityLog = new ActivityLog();
    }

    /** GET /admin/menu */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        // Get filter params
        $search = trim($this->input('search', ''));
        
        // Pagination settings
        $page = max(1, (int) $this->input('page', 1));
        $limit = 20; // Items per page
        
        // Get all items
        $allItems = $this->itemModel->getAll();
        
        // Filter items based on parameters
        $filteredItems = array_filter($allItems, function($item) use ($search) {
            // Search filter
            if ($search !== '') {
                $searchLower = mb_strtolower($search);
                $nameMatch = mb_stripos($item['name'] ?? '', $searchLower) !== false;
                $nameEnMatch = mb_stripos($item['name_en'] ?? '', $searchLower) !== false;
                if (!$nameMatch && !$nameEnMatch) {
                    return false;
                }
            }
            return true;
        });
        
        // Pagination for filtered items
        $total = count($filteredItems);
        $totalPages = max(1, ceil($total / $limit));
        $page = min($page, $totalPages); // Ensure page doesn't exceed total pages
        $offset = ($page - 1) * $limit;
        
        // Slice items for current page
        $items = array_slice($filteredItems, $offset, $limit);
        
        $categories = $this->categoryModel->getAll();

        $this->view('layouts/admin', [
            'view' => 'admin/menu/index',
            'pageTitle' => 'Quản lý Món ăn',
            'pageSubtitle' => $total . ' món (Trang ' . $page . '/' . $totalPages . ')',
            'items' => $items,
            'categories' => $categories,
            'currentFilters' => [
                'search' => $search
            ],
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'limit' => $limit
        ]);
    }

    /** GET /admin/menu/create */
    public function create(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $categories = $this->categoryModel->getActive();
        $menuTypes = $this->typeModel->getActive();
        $this->view('layouts/admin', [
            'view' => 'admin/menu/form',
            'pageTitle' => 'Thêm Món',
            'categories' => $categories,
            'menuTypes' => $menuTypes,
            'item' => null,
        ]);
    }

    /** POST /admin/menu/store */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $data = $this->collectFormData();

        // Auto calculate sort_order: max + 1
        $allItems = $this->itemModel->getAll();
        $maxSort = 0;
        foreach ($allItems as $item) {
            if ((int)($item['sort_order'] ?? 0) > $maxSort) {
                $maxSort = (int)($item['sort_order'] ?? 0);
            }
        }
        $data['sort_order'] = $maxSort + 1;

        if (!empty($_FILES['image']['name'])) {
            $uploaded = uploadMenuImage($_FILES['image']);
            if ($uploaded)
                $data['image'] = $uploaded;
        }

        $id = $this->itemModel->create($data);
        $this->handleGalleryUpload($id);

        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_CREATE,
            'menu_item',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm món thành công!'];
        $this->redirect('/admin/menu');
    }

    /** GET /admin/menu/edit?id= */
    public function edit(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->itemModel->findById($id);
        if (!$item)
            $this->redirect('/admin/menu');

        $categories = $this->categoryModel->getActive();
        $menuTypes = $this->typeModel->getActive();
        $this->view('layouts/admin', [
            'view' => 'admin/menu/form',
            'pageTitle' => 'Sửa Món',
            'categories' => $categories,
            'menuTypes' => $menuTypes,
            'item' => $item,
        ]);
    }

    /** POST /admin/menu/update */
    public function update(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $data = $this->collectFormData();

        // Giữ lại các tham số filter và pagination
        $page = $this->input('page', 1);
        $service = $this->input('service', '');
        $category = $this->input('category', '');
        $status = $this->input('status', '');
        $search = $this->input('search', '');
        // Không lấy menu_type từ form vì đây là loại của món, không phải filter
        $tag = $this->input('tag', '');
        $stockStatus = $this->input('stock_status', '');
        $priceRange = $this->input('price_range', '');

        if (!empty($_FILES['image']['name'])) {
            $uploaded = uploadMenuImage($_FILES['image']);
            if ($uploaded) {
                $data['image'] = $uploaded;
                $this->itemModel->updateImage($id, $uploaded);
            }
        }

        $this->itemModel->update($id, $data);
        $this->handleGalleryUpload($id);

        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'menu_item',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật món!'];
        
        // Build redirect URL with filters and page (không bao gồm menu_type)
        $params = array_filter([
            'page' => $page,
            'service' => $service,
            'category' => $category,
            'status' => $status,
            'search' => $search,
            'tag' => $tag,
            'stock_status' => $stockStatus,
            'price_range' => $priceRange,
        ], fn($v) => $v !== '' && $v !== null);
        
        $query = http_build_query($params);
        $redirectUrl = '/admin/menu' . ($query ? '?' . $query : '');
        $this->redirect($redirectUrl);
    }

    /** POST /admin/menu/delete */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID món không hợp lệ.'];
            $this->redirect('/admin/menu');
        }

        try {
            $db = getDB();

            // Kiểm tra món có đang được tham chiếu trong lịch sử order không
            $stmt = $db->prepare("SELECT COUNT(*) FROM order_items WHERE menu_item_id = ?");
            $stmt->execute([$id]);
            $inUse = (int) $stmt->fetchColumn();

            if ($inUse > 0) {
                // Có FK → soft delete: ẩn khỏi menu thay vì xóa cứng
                $db->prepare("UPDATE menu_items SET is_active = 0, is_available = 0 WHERE id = ?")
                   ->execute([$id]);
                
                // Log activity
                $this->activityLog->log(
                    ActivityLog::ACTION_DELETE,
                    'menu_item',
                    $id,
                    ['reason' => 'in_use', 'order_count' => $inUse],
                    ActivityLog::LEVEL_WARNING
                );
                
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => "Món này đang có trong {$inUse} lịch sử đơn hàng, không thể xóa hoàn toàn. Đã ẩn khỏi menu.",
                ];
            } else {
                // Không có FK → xóa cứng bình thường
                $this->itemModel->delete($id);
                
                // Log activity
                $this->activityLog->log(
                    ActivityLog::ACTION_DELETE,
                    'menu_item',
                    $id,
                    ['reason' => 'not_in_use'],
                    ActivityLog::LEVEL_INFO
                );
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa món thành công.'];
            }
        } catch (\Throwable $e) {
            // Fallback: nếu vẫn lỗi FK → chỉ ẩn món
            try {
                getDB()->prepare("UPDATE menu_items SET is_active = 0, is_available = 0 WHERE id = ?")
                       ->execute([$id]);
                $_SESSION['flash'] = [
                    'type'    => 'warning',
                    'message' => 'Không thể xóa hoàn toàn (đang có dữ liệu liên kết). Đã ẩn khỏi menu.',
                ];
            } catch (\Throwable $e2) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi xóa món: ' . $e->getMessage()];
            }
        }

        $this->redirect('/admin/menu');
    }

    /** POST /admin/menu/toggle — Toggle hết hàng/còn hàng */
    public function toggle(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $type = (string) $this->input('type', 'available'); // available | active

        if ($type === 'active') {
            $this->itemModel->toggleActive($id);
        } else {
            $this->itemModel->toggleAvailable($id);
        }

        $item = $this->itemModel->findById($id);
        $this->json(['ok' => true, 'is_available' => $item['is_available'], 'is_active' => $item['is_active']]);
    }

    private function collectFormData(): array
    {
        $tags = $this->input('tags', []);
        if (!is_array($tags)) $tags = [];

        return [
            'category_id'     => (int) $this->input('category_id'),
            'name'            => trim((string) $this->input('name', '')),
            'name_en'         => trim((string) $this->input('name_en', '')) ?: null,
            'description'     => trim((string) $this->input('description', '')) ?: null,
            'price'           => (float) $this->input('price', 0),
            'stock'           => (int) $this->input('stock', -1),
            'tags'            => !empty($tags) ? implode(',', array_unique($tags)) : null,
            'note_options'    => trim((string) $this->input('note_options', '')) ?: null,
            'note_options_en' => trim((string) $this->input('note_options_en', '')) ?: null,
            'sort_order'      => (int) $this->input('sort_order', 0),
            'is_active'       => (int) $this->input('is_active', 1),
            'service_type'    => trim((string) $this->input('service_type', 'both')),
            'menu_type'       => trim((string) $this->input('menu_type', 'asia')),
        ];
    }

    private function handleGalleryUpload(int $itemId): void
    {
        if (empty($_FILES['gallery']['name'][0]))
            return;

        foreach ($_FILES['gallery']['name'] as $key => $name) {
            if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $name,
                    'type' => $_FILES['gallery']['type'][$key],
                    'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                    'error' => $_FILES['gallery']['error'][$key],
                    'size' => $_FILES['gallery']['size'][$key]
                ];
                $uploaded = uploadMenuImage($file);
                if ($uploaded) {
                    $this->itemModel->addGalleryImage($itemId, $uploaded);
                }
            }
        }
    }

    /** GET /admin/menu/clear — Clear menu data page */
    public function clearPage(): void
    {
        Auth::requireRole(ROLE_IT);

        // Count current data
        $db = getDB();
        $itemsCount = (int) $db->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
        $categoriesCount = (int) $db->query("SELECT COUNT(*) FROM menu_categories")->fetchColumn();
        $setsCount = (int) $db->query("SELECT COUNT(*) FROM menu_sets")->fetchColumn();
        $setItemsCount = (int) $db->query("SELECT COUNT(*) FROM menu_set_items")->fetchColumn();

        $this->view('layouts/admin', [
            'view' => 'admin/menu/clear',
            'pageTitle' => 'Xóa dữ liệu thực đơn',
            'counts' => [
                'items' => $itemsCount,
                'categories' => $categoriesCount,
                'sets' => $setsCount,
                'setItems' => $setItemsCount,
            ],
        ]);
    }

    /** POST /admin/menu/clear — Clear menu data */
    public function clear(): void
    {
        Auth::requireRole(ROLE_IT);

        $type = $this->input('type', 'all'); // all, items, categories, sets
        $confirm = $this->input('confirm', '');

        if ($confirm !== 'YES_CLEAR_ALL') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng xác nhận xóa dữ liệu.'];
            $this->redirect('/admin/menu/clear');
            return;
        }

        try {
            $db = getDB();
            $db->beginTransaction();

            $deleted = [
                'items' => 0,
                'categories' => 0,
                'sets' => 0,
                'setItems' => 0,
            ];

            if ($type === 'all' || $type === 'items') {
                // Xóa các món ăn (không có FK ràng buộc)
                $stmt = $db->prepare("DELETE FROM menu_items");
                $stmt->execute();
                $deleted['items'] = $stmt->rowCount();

                // Xóa các mục trong menu_set_items
                $stmt = $db->prepare("DELETE FROM menu_set_items");
                $stmt->execute();
                $deleted['setItems'] = $stmt->rowCount();
            }

            if ($type === 'all' || $type === 'categories') {
                // Xóa danh mục
                $stmt = $db->prepare("DELETE FROM menu_categories");
                $stmt->execute();
                $deleted['categories'] = $stmt->rowCount();
            }

            if ($type === 'all' || $type === 'sets') {
                // Xóa set combo
                $stmt = $db->prepare("DELETE FROM menu_sets");
                $stmt->execute();
                $deleted['sets'] = $stmt->rowCount();
            }

            $db->commit();

            // Log activity
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'menu_clear',
                0,
                [
                    'type' => $type,
                    'deleted' => $deleted,
                    'user_id' => Auth::user()['id'],
                ],
                ActivityLog::LEVEL_WARNING
            );

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => sprintf(
                    'Đã xóa thành công: %d món, %d danh mục, %d set, %d món trong set',
                    $deleted['items'],
                    $deleted['categories'],
                    $deleted['sets'],
                    $deleted['setItems']
                ),
            ];
        } catch (\Throwable $e) {
            $db->rollBack();
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi khi xóa: ' . $e->getMessage()];
        }

        $this->redirect('/admin/menu/clear');
    }
}
