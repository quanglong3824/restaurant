<?php // views/admin/menu/index.php ?>

<?php
// Get current filters
$currentSearch = $currentFilters['search'] ?? '';
$currentPage = $page ?? 1;

// Helper function to build URL with filters using current filter variables
function buildMenuUrl($search, $page = 1) {
    $params = array_filter([
        'search' => $search,
        'page' => $page > 1 ? $page : null,
    ], fn($v) => $v !== '' && $v !== null);
    
    $query = http_build_query($params);
    return BASE_URL . '/admin/menu' . ($query ? '?' . $query : '');
}
?>

<div class="card">
    <div class="card-header" style="flex-direction:column;align-items:flex-start;gap:1rem;">
        
        <div style="display:flex;align-items:center;justify-content:space-between;width:100%;flex-wrap:wrap;gap:.75rem;">
            <h2 style="margin:0;"><i class="fas fa-utensils"></i> Danh sách Món ăn
                <span id="countBadge" style="font-size:.75rem;font-weight:600;background:var(--gold);color:#fff;padding:.15rem .65rem;border-radius:20px;margin-left:.5rem;vertical-align:middle;"><?= $total ?? 0 ?> món</span>
            </h2>
            <div style="display:flex;gap:.5rem;">
                <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline <?= !isset($_GET['type']) || $_GET['type'] === '' ? 'active' : '' ?>" style="text-decoration:none;">
                    <i class="fas fa-utensils"></i> Món Lẻ
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/sets" class="btn btn-outline <?= isset($_GET['type']) && $_GET['type'] === 'sets' ? 'active' : '' ?>" style="text-decoration:none;">
                    <i class="fas fa-layer-group"></i> Set & Combo
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/create" class="btn btn-gold" style="text-decoration:none;">
                    <i class="fas fa-plus"></i> Thêm món
                </a>
                <?php if (Auth::check() && Auth::user()['role'] === ROLE_IT): ?>
                <a href="<?= BASE_URL ?>/admin/menu/clear" class="btn btn-outline" style="border-color:#dc2626;color:#dc2626;text-decoration:none;" title="Xóa dữ liệu thực đơn (IT only)">
                    <i class="fas fa-trash-alt"></i> Xóa dữ liệu
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── FILTER BAR ──────────────────────────────────────── -->
        <form method="GET" action="<?= BASE_URL ?>/admin/menu" class="filter-bar" id="filterForm">
            
            <!-- Search -->
            <div class="filter-input-wrap" style="flex-grow: 1;">
                <i class="fas fa-search filter-icon"></i>
                <input type="text" name="search" id="searchInput" class="filter-input" placeholder="Tìm tên món..." value="<?= e($currentSearch) ?>">
                <button type="button" id="clearSearch" class="filter-clear" style="display:<?= $currentSearch ? '' : 'none' ?>;" onclick="clearSearchInput()" title="Xóa">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Current page hidden input to preserve pagination -->
            <input type="hidden" name="page" value="<?= $currentPage ?>">
            
            <!-- Filter Button -->
            <button type="submit" class="btn btn-gold btn-sm" title="Tìm kiếm">
                <i class="fas fa-search"></i> Tìm
            </button>
            
            <!-- Reset -->
            <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-sm" title="Xóa tìm kiếm">
                <i class="fas fa-rotate-left"></i>
            </a>
        </form>
    </div>

    <!-- Pagination top -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;border-bottom:2px solid var(--border-color);margin-bottom:1rem;">
        <span style="color:#64748b;font-size:.85rem;">
            Trang <strong><?= $page ?>/<?= $totalPages ?></strong> 
            (<?= $total ?> món)
        </span>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
            <?php
            // First page
            if ($currentPage > 1):
            ?>
            <a href="<?= buildMenuUrl($currentSearch, 1) ?>" class="btn btn-outline btn-sm"><i class="fas fa-angles-left"></i> Đầu</a>
            <?php endif; ?>
            
            <!-- Previous page -->
            <?php if ($currentPage > 1): ?>
            <a href="<?= buildMenuUrl($currentSearch, $currentPage - 1) ?>" class="btn btn-outline btn-sm"><i class="fas fa-angle-left"></i> Trước</a>
            <?php endif; ?>
            
            <!-- Page numbers -->
            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            
            if ($startPage > 1):
            ?>
            <span class="btn btn-outline btn-sm" style="cursor:default;">...</span>
            <?php endif; ?>
            
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a href="<?= buildMenuUrl($currentSearch, $i) ?>" class="btn btn-sm <?= $i === $currentPage ? 'btn-gold' : 'btn-outline' ?>" style="text-decoration:none;min-width:40px;"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($endPage < $totalPages): ?>
            <span class="btn btn-outline btn-sm" style="cursor:default;">...</span>
            <?php endif; ?>
            
            <!-- Next page -->
            <?php if ($currentPage < $totalPages): ?>
            <a href="<?= buildMenuUrl($currentSearch, $currentPage + 1) ?>" class="btn btn-outline btn-sm">Sau <i class="fas fa-angle-right"></i></a>
            <?php endif; ?>
            
            <!-- Last page -->
            <?php if ($currentPage < $totalPages): ?>
            <a href="<?= buildMenuUrl($currentSearch, $totalPages) ?>" class="btn btn-outline btn-sm">Cuối <i class="fas fa-angles-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="table-wrap">
        <table id="menuTable">
            <thead>
                <tr>
                    <th style="width:56px;">Ảnh</th>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Loại món</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th style="width:80px;">Hiển thị</th>
                    <th style="width:100px;">Còn hàng</th>
                    <th style="width:110px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr data-id="<?= $item['id'] ?>"
                        data-cat="<?= $item['category_id'] ?>"
                        data-menu-type="<?= $item['menu_type'] ?? '' ?>"
                        data-name="<?= strtolower(e($item['name'])) ?> <?= strtolower(e($item['name_en'] ?? '')) ?>"
                        data-active="<?= $item['is_active'] ?>"
                        data-available="<?= $item['is_available'] ?>">
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt=""
                                    style="width:44px;height:44px;object-fit:cover;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);">
                            <?php else: ?>
                                <div style="width:44px;height:44px;background:linear-gradient(135deg,#f3f4f6,#e5e7eb);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($item['name']) ?></strong>
                            <?php if ($item['name_en']): ?>
                                <span style="display:block;font-size:.75rem;color:#9ca3af;"><?= e($item['name_en']) ?></span>
                            <?php endif; ?>
                            <?php
                            $noteChips = array_filter(array_map('trim', explode(',', $item['note_options'] ?? '')));
                            if (!empty($noteChips)): ?>
                            <div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.35rem;">
                                <?php foreach ($noteChips as $chip): ?>
                                <span style="background:rgba(212,175,55,.12);color:var(--gold-dark,#785e0a);border:1px solid rgba(212,175,55,.4);border-radius:12px;padding:.1rem .45rem;font-size:.62rem;font-weight:700;">
                                    <i class="fas fa-tag" style="font-size:.5rem;opacity:.7;"></i> <?= e($chip) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:.85rem;color:#64748b;"><?= e($item['category_name'] ?? '') ?></td>
                        <td>
                            <?php
                            $menuTypeLabels = [
                                'asia' => ['label' => 'Món Á', 'icon' => 'fa-bowl-rice', 'color' => '#dc2626'],
                                'europe' => ['label' => 'Món Âu', 'icon' => 'fa-wine-glass', 'color' => '#2563eb'],
                                'alacarte' => ['label' => 'Alacarte', 'icon' => 'fa-utensils', 'color' => '#059669'],
                                'other' => ['label' => 'Khác', 'icon' => 'fa-circle-question', 'color' => '#7c3aed'],
                            ];
                            $mt = $item['menu_type'] ?? 'other';
                            $mtInfo = $menuTypeLabels[$mt] ?? $menuTypeLabels['other'];
                            ?>
                            <span class="menu-type-badge" style="--mt-c:<?= $mtInfo['color'] ?>">
                                <i class="fas <?= $mtInfo['icon'] ?>"></i> <?= $mtInfo['label'] ?>
                            </span>
                        </td>
                        <td><strong style="color:var(--gold);font-size:.95rem;"><?= formatPrice($item['price']) ?></strong></td>
                        <td>
                            <?php if (!isset($item['stock']) || $item['stock'] == -1): ?>
                                <span style="font-size:.72rem;color:#16a34a;font-weight:700;">∞ Không giới hạn</span>
                            <?php elseif ($item['stock'] < 5): ?>
                                <span class="badge" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;"><?= $item['stock'] ?> còn lại</span>
                            <?php else: ?>
                                <span class="badge" style="background:#f0fdf4;color:#16a34a;border:1px solid #86efac;"><?= $item['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_active'] ? 'toggle-btn--on' : '' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'active', this)"
                                title="<?= $item['is_active'] ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện' ?>">
                                <i class="fas <?= $item['is_active'] ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                            </button>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_available'] ? 'toggle-btn--on' : 'toggle-btn--off' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'available', this)"
                                title="<?= $item['is_available'] ? 'Còn hàng — Click để đánh Hết' : 'Hết hàng — Click để Mở lại' ?>">
                                <?= $item['is_available'] ? 'Còn hàng' : 'Hết hàng' ?>
                            </button>
                        </td>
                        <td>
                            <div style="display:flex;gap:.35rem;">
                                <a href="<?= BASE_URL ?>/admin/menu/edit?id=<?= $item['id'] ?>&page=<?= $currentPage ?>&search=<?= urlencode($currentSearch) ?>"
                                    class="btn btn-outline btn-sm" title="Sửa món"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="<?= BASE_URL ?>/admin/menu/delete" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                        data-confirm="Xóa món '<?= e($item['name']) ?>'?" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr><td colspan="9" style="text-align:center;padding:2.5rem;color:#9ca3af;">
                        <i class="fas fa-utensils fa-2x" style="opacity:.3;display:block;margin-bottom:.75rem;"></i>
                        Chưa có món nào.
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- No results state -->
        <div id="noResultsState" style="display:none;text-align:center;padding:3rem 1rem;color:#9ca3af;">
            <i class="fas fa-search fa-2x" style="opacity:.3;display:block;margin-bottom:.75rem;"></i>
            <p style="font-weight:600;">Không tìm thấy món phù hợp</p>
            <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-sm" style="margin-top:.5rem;">
                <i class="fas fa-rotate-left"></i> Xóa tìm kiếm
            </a>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;border-top:2px solid var(--border-color);margin-top:1rem;">
            <span style="color:#64748b;font-size:.85rem;">
                Trang <strong><?= $page ?>/<?= $totalPages ?></strong> 
                (<?= $total ?> món)
            </span>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <?php
                // First page
                if ($currentPage > 1):
                ?>
                <a href="<?= buildMenuUrl($currentSearch, 1) ?>" class="btn btn-outline btn-sm"><i class="fas fa-angles-left"></i> Đầu</a>
                <?php endif; ?>
                
                <!-- Previous page -->
                <?php if ($currentPage > 1): ?>
                <a href="<?= buildMenuUrl($currentSearch, $currentPage - 1) ?>" class="btn btn-outline btn-sm"><i class="fas fa-angle-left"></i> Trước</a>
                <?php endif; ?>
                
                <!-- Page numbers -->
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1):
                ?>
                <span class="btn btn-outline btn-sm" style="cursor:default;">...</span>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="<?= buildMenuUrl($currentSearch, $i) ?>" class="btn btn-sm <?= $i === $currentPage ? 'btn-gold' : 'btn-outline' ?>" style="text-decoration:none;min-width:40px;"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages): ?>
                <span class="btn btn-outline btn-sm" style="cursor:default;">...</span>
                <?php endif; ?>
                
                <!-- Next page -->
                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= buildMenuUrl($currentSearch, $currentPage + 1) ?>" class="btn btn-outline btn-sm">Sau <i class="fas fa-angle-right"></i></a>
                <?php endif; ?>
                
                <!-- Last page -->
                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= buildMenuUrl($currentSearch, $totalPages) ?>" class="btn btn-outline btn-sm">Cuối <i class="fas fa-angles-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* ── Stat Chips ──────────────────────────────────────────── */
.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .9rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 600;
    border: 1.5px solid var(--border-color, #e5e7eb);
    background: transparent;
    color: var(--text-secondary, #64748b);
    cursor: pointer;
    transition: all .18s;
    text-decoration: none;
}
.stat-chip:hover { border-color: var(--gold); color: var(--gold); }
.stat-chip.active {
    background: var(--gold);
    border-color: var(--gold);
    color: #fff;
    box-shadow: 0 2px 8px rgba(212,175,55,.35);
}
.chip-count {
    background: rgba(255,255,255,.25);
    border-radius: 10px;
    padding: .05rem .45rem;
    font-size: .72rem;
    font-weight: 700;
}
.stat-chip:not(.active) .chip-count {
    background: rgba(0,0,0,.07);
}

/* ── Filter Bar ──────────────────────────────────────────── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: .6rem;
    flex-wrap: wrap;
    width: 100%;
    padding: .65rem .85rem;
    background: var(--card-bg-alt, #f8fafc);
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 12px;
}
.filter-input-wrap,
.filter-select-wrap {
    display: flex;
    align-items: center;
    gap: .45rem;
    background: #fff;
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 8px;
    padding: 0 .65rem;
    transition: border-color .18s;
}
.filter-input-wrap:focus-within,
.filter-select-wrap:focus-within {
    border-color: var(--gold, #d4af37);
    box-shadow: 0 0 0 3px rgba(212,175,55,.1);
}
.filter-icon {
    color: #9ca3af;
    font-size: .8rem;
    flex-shrink: 0;
}
.filter-input {
    border: none !important;
    outline: none !important;
    font-size: .875rem;
    padding: .5rem 0;
    background: transparent;
    min-width: 180px;
    color: var(--text-primary, #1e293b);
}
.filter-input::placeholder { color: #bdc3cc; }
.filter-select {
    border: none !important;
    outline: none !important;
    font-size: .875rem;
    padding: .5rem 0;
    background: transparent;
    color: var(--text-primary, #1e293b);
    cursor: pointer;
    min-width: 155px;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right .25rem center;
    background-size: 1.1em;
    padding-right: 1.6rem;
}
.filter-clear {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0 .1rem;
    font-size: .8rem;
    line-height: 1;
    transition: color .15s;
}
.filter-clear:hover { color: var(--danger, #dc2626); }

/* ── Service Badge ───────────────────────────────────────── */
.service-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: color-mix(in srgb, var(--c) 12%, transparent);
    color: var(--c);
    border: 1.5px solid color-mix(in srgb, var(--c) 35%, transparent);
    border-radius: 20px;
    padding: .2rem .7rem;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}

/* Fallback cho browser cũ không hỗ trợ color-mix */
@supports not (color: color-mix(in srgb, red, blue)) {
    .service-badge { background: rgba(100,100,100,.1); border-color: rgba(100,100,100,.3); }
}

/* ── Menu Type Badge ─────────────────────────────────────── */
.menu-type-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: color-mix(in srgb, var(--mt-c) 12%, transparent);
    color: var(--mt-c);
    border: 1.5px solid color-mix(in srgb, var(--mt-c) 35%, transparent);
    border-radius: 12px;
    padding: .15rem .5rem;
    font-size: .68rem;
    font-weight: 700;
    white-space: nowrap;
}

@supports not (color: color-mix(in srgb, red, blue)) {
    .menu-type-badge { background: rgba(100,100,100,.1); border-color: rgba(100,100,100,.3); }
}

/* ── Highlight row after save ─────────────────────────────── */
@keyframes rowHighlight {
    0%   { background: rgba(34,197,94,.22); box-shadow: inset 0 0 0 2px rgba(34,197,94,.6); }
    60%  { background: rgba(34,197,94,.12); box-shadow: inset 0 0 0 2px rgba(34,197,94,.25); }
    100% { background: transparent;          box-shadow: none; }
}
.row-highlighted {
    animation: rowHighlight 2.4s ease forwards;
}
</style>

<script>
/* ── Highlight saved row ─────────────────────────────────── */
(function() {
    const params = new URLSearchParams(window.location.search);
    const hlId = params.get('highlighted');
    if (!hlId) return;
    const row = document.querySelector('tr[data-id="' + hlId + '"]');
    if (row) {
        row.classList.add('row-highlighted');
        // Scroll row into view smoothly
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    // Clean URL (remove highlighted param) without reload
    params.delete('highlighted');
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    history.replaceState(null, '', newUrl);
})();

/* ── Search with debounce ────────────────────────────────── */
let _debounceTimer;
document.getElementById('searchInput').addEventListener('input', function() {
    const clearBtn = document.getElementById('clearSearch');
    clearBtn.style.display = this.value ? '' : 'none';
    clearTimeout(_debounceTimer);
    _debounceTimer = setTimeout(() => {
        // Reset page to 1 when search changes
        document.getElementById('filterForm').submit();
    }, 400);
}
function clearSearchInput() {
    document.getElementById('searchInput').value = '';
    document.getElementById('clearSearch').style.display = 'none';
    // Reset page to 1 when clearing search
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
}

/* ── Auto submit on filter change ───────────────────────── */
document.getElementById('catFilter').addEventListener('change', function() {
    // Reset page to 1 when category changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});
document.getElementById('statusFilter').addEventListener('change', function() {
    // Reset page to 1 when status changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});
document.getElementById('menuTypeFilter').addEventListener('change', function() {
    // Reset page to 1 when menu type changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});
document.getElementById('tagFilter').addEventListener('change', function() {
    // Reset page to 1 when tag changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});
document.getElementById('stockStatusFilter').addEventListener('change', function() {
    // Reset page to 1 when stock status changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});
document.getElementById('priceRangeFilter').addEventListener('change', function() {
    // Reset page to 1 when price range changes
    document.querySelector('[name="page"]').value = 1;
    document.getElementById('filterForm').submit();
});

/* ── Toggle item ─────────────────────────────────────────── */
function toggleItem(id, type, btn) {
    fetch('<?= BASE_URL ?>/admin/menu/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id, type })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.ok) return;
        const row = btn.closest('tr');
        if (type === 'available') {
            const on = data.is_available == 1;
            btn.textContent = on ? 'Còn hàng' : 'Hết hàng';
            btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : 'toggle-btn--off');
            row.dataset.available = on ? '1' : '0';
        } else {
            const on = data.is_active == 1;
            btn.innerHTML = '<i class="fas ' + (on ? 'fa-eye' : 'fa-eye-slash') + '"></i>';
            btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : '');
            btn.title = on ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện';
            row.dataset.active = on ? '1' : '0';
        }
        // Reload page to update counts
        window.location.reload();
    });
}
</script>