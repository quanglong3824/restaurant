<?php
// ============================================================
// Helper Functions — Aurora Restaurant
// ============================================================

/**
 * Format giá tiền VND - Chuẩn Châu Âu
 * Format: 1000000 → "1.000.000 VND"
 */
function formatPrice(int|float|null $amount): string
{
    return number_format((float) $amount, 0, ',', '.') . ' VND';
}

/**
 * Escape HTML
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Helper gọi file asset với Cache Busting (đặc biệt cho Safari iPad trên Prod)
 */
function asset(string $path): string
{
    $baseUrl = defined('BASE_URL') ? BASE_URL : '';
    $fullPath = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

    // Luôn thêm version bằng timestamp trên Prod để iPad không cache CSS/JS cũ
    $version = APP_VERSION . '-' . date('YmdHi');
    return $fullPath . '?v=' . $version;
}

/**
 * Active nav class helper
 */
function activeClass(string $path): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Get path from BASE_URL
    $baseUrlPath = parse_url(defined('BASE_URL') ? BASE_URL : '', PHP_URL_PATH);
    $baseUrlPath = rtrim($baseUrlPath, '/');

    $lookupPath = $baseUrlPath . '/' . ltrim($path, '/');
    $lookupPath = rtrim($lookupPath, '/');
    $uri = rtrim($uri, '/');

    return ($uri === $lookupPath) ? 'active' : '';
}

/**
 * Redirect helper (standalone)
 */
function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

/**
 * Upload ảnh món ăn
 */
function uploadMenuImage(array $file): string|false
{
    if ($file['error'] !== UPLOAD_ERR_OK)
        return false;
    if ($file['size'] > MAX_UPLOAD_SIZE)
        return false;
    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES))
        return false;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('menu_', true) . '.' . $ext;
    $dest = UPLOAD_PATH . 'menu/' . $filename;

    if (!is_dir(UPLOAD_PATH . 'menu/')) {
        mkdir(UPLOAD_PATH . 'menu/', 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'menu/' . $filename;
    }
    return false;
}

/**
 * Role label tiếng Việt
 */
function roleLabel(string $role): string
{
    return match ($role) {
        'waiter' => 'Phục vụ',
        'admin' => 'Admin',
        'it' => 'IT',
        default => $role,
    };
}

/**
 * Time ago
 */
function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60)
        return 'vừa xong';
    if ($diff < 3600)
        return floor($diff / 60) . ' phút trước';
    if ($diff < 86400)
        return floor($diff / 3600) . ' giờ trước';
    return date('d/m/Y H:i', strtotime($datetime));
}

/**
 * Pagination helper - Generate pagination HTML
 */
function renderPagination(int $page, int $totalPages, string $baseUrl, array $params = []): string
{
    if ($totalPages <= 1) return '';
    
    $html = '<div class="activity-pagination">';
    $html .= '<div class="pagination-info">';
    $html .= 'Trang ' . $page . ' / ' . $totalPages;
    $html .= '</div>';
    $html .= '<div class="pagination-buttons">';
    
    // First page
    $firstUrl = buildPaginationUrl($baseUrl, $params, 1);
    $html .= '<button onclick="window.location.href=\'' . $firstUrl . '\'"' . ($page === 1 ? ' disabled' : '') . '>';
    $html .= '<i class="fas fa-angle-double-left"></i>';
    $html .= '</button>';
    
    // Previous page
    $prevPage = max(1, $page - 1);
    $prevUrl = buildPaginationUrl($baseUrl, $params, $prevPage);
    $html .= '<button onclick="window.location.href=\'' . $prevUrl . '\'"' . ($page === 1 ? ' disabled' : '') . '>';
    $html .= '<i class="fas fa-angle-left"></i>';
    $html .= '</button>';
    
    // Page numbers
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        $pageUrl = buildPaginationUrl($baseUrl, $params, $i);
        $activeClass = ($i === $page) ? ' active' : '';
        $html .= '<button onclick="window.location.href=\'' . $pageUrl . '\'" class="' . $activeClass . '">';
        $html .= $i;
        $html .= '</button>';
    }
    
    // Next page
    $nextPage = min($totalPages, $page + 1);
    $nextUrl = buildPaginationUrl($baseUrl, $params, $nextPage);
    $html .= '<button onclick="window.location.href=\'' . $nextUrl . '\'"' . ($page === $totalPages ? ' disabled' : '') . '>';
    $html .= '<i class="fas fa-angle-right"></i>';
    $html .= '</button>';
    
    // Last page
    $lastUrl = buildPaginationUrl($baseUrl, $params, $totalPages);
    $html .= '<button onclick="window.location.href=\'' . $lastUrl . '\'"' . ($page === $totalPages ? ' disabled' : '') . '>';
    $html .= '<i class="fas fa-angle-double-right"></i>';
    $html .= '</button>';
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Build pagination URL with query parameters
 */
function buildPaginationUrl(string $baseUrl, array $params, int $page): string
{
    $params['page'] = $page;
    return $baseUrl . '?' . http_build_query($params);
}

/**
 * Get pagination data
 */
function getPaginationData(int $total, int $page, int $limit): array
{
    $totalPages = ceil($total / $limit);
    $offset = ($page - 1) * $limit;
    
    return [
        'page' => $page,
        'limit' => $limit,
        'total' => $total,
        'totalPages' => $totalPages,
        'offset' => $offset,
        'hasPrev' => $page > 1,
        'hasNext' => $page < $totalPages,
    ];
}
