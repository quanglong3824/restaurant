<?php // views/menu/customer.php — Customer Digital Menu
$currentLang = $_COOKIE['aurora_lang'] ?? 'vi';
// Xác định ngữ cảnh phục vụ dựa trên type của bàn/phòng
$isRoomService = isset($table['type']) && $table['type'] === 'room';
$contextLabel  = $isRoomService ? 'ROOM SERVICE' : 'RESTAURANT';
$contextIcon   = $isRoomService ? 'fa-bed' : 'fa-utensils';
$contextColor  = $isRoomService ? '#8b5cf6' : 'var(--gold)';

// Tính $hasItems
$hasItems = false;
if (isset($orderItems) && count($orderItems) > 0) {
    foreach ($orderItems as $oi) {
        if ($oi['status'] !== 'cancelled') { $hasItems = true; break; }
    }
}

// Nhóm menuItems theo category_id (đã lọc đúng service_type từ controller)
$grouped = [];
foreach ($menuItems as $mi) {
    $grouped[$mi['category_id']][] = $mi;
}
// Chỉ lấy categories có món
$activeCategories = array_filter($categories, fn($c) => isset($grouped[$c['id']]));

// Tổng tiền order hiện tại
$orderTotal = 0;
if ($hasItems) {
    foreach ($orderItems as $oi) {
        if ($oi['status'] !== 'cancelled') $orderTotal += $oi['item_price'] * $oi['quantity'];
    }
}
?>

<!-- ══════════════════════════════════════════════════════
     OVERLAYS: Location Check & Out-of-range
═══════════════════════════════════════════════════════ -->
<script>
// Send location data to server for persistent storage
(function sendLocationToServer() {
    var locationData = localStorage.getItem('qr_location_' + CUSTOMER_CONFIG.tableId);
    if (locationData) {
        fetch('<?= BASE_URL ?>/qr/menu/location', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'location_data=' + encodeURIComponent(locationData) + '&table_id=' + <?= $table['id'] ?>
        }).catch(function(){});
    }
})();
</script>
<div id="frozenOverlay" class="loc-overlay" style="display:none;">
    <div class="loc-card" style="--card-accent:#ef4444;">
        <div class="loc-icon-ring" style="color:#ef4444;">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3 style="color:#ef4444;">BẠN ĐÃ RỜI KHỎI KHU VỰC</h3>
        <p class="loc-sub">Thực đơn tạm thời bị khoá để bảo mật đơn hàng</p>
        <div class="loc-dist-badge err">
            <i class="fas fa-walking"></i> Khoảng cách: <span id="frozenDistVal">...</span>m
        </div>
        <p class="loc-hint">Vui lòng quay lại khu vực để tiếp tục</p>
    </div>
</div>

<div id="locationOverlay" class="loc-overlay">
    <script>
        (function(){
            var _tid = <?= (int)$table['id'] ?>;
            var _key = 'locationVerified_table_' + _tid;
            if (localStorage.getItem(_key) === 'true') {
                document.getElementById('locationOverlay').style.display = 'none';
            }
        })();
    </script>
    <div class="loc-card">
u         <div class="loc-icon-ring"><i class="fas fa-shield-alt"></i></div>
        <h3 class="loc-title" data-vi="XÁC NHẬN HIỆN DIỆN / CONFIRM YOUR PRESENCE" data-en="CONFIRM YOUR PRESENCE">XÁC NHẬN HIỆN DIỆN / CONFIRM YOUR PRESENCE</h3>
        <p class="loc-sub">AURORA HOTEL PLAZA</p>
        <div id="liveDistance" class="loc-dist-badge" style="display:none;">
            <i class="fas fa-map-marker-alt"></i> <span id="distVal">...</span>m
        </div>
        <p class="loc-desc" data-vi="Để bảo mật đơn hàng và tốc độ phục vụ tối ưu, vui lòng xác nhận vị trí của bạn. / For order security and optimal service speed, please confirm your location." data-en="For order security and optimal service speed, please confirm your location.">
            Để bảo mật đơn hàng và tốc độ phục vụ tối ưu, vui lòng xác nhận vị trí của bạn. / For order security and optimal service speed, please confirm your location.
        </p>
        <ul class="loc-benefits">
            <li><i class="fas fa-check-circle"></i> <span data-vi="Đơn hàng xác nhận ngay lập tức / Instant order confirmation" data-en="Instant order confirmation">Đơn hàng xác nhận ngay lập tức / Instant order confirmation</span></li>
            <li><i class="fas fa-lock"></i> <span data-vi="Không lưu lịch sử vị trí / No location history stored" data-en="No location history stored">Không lưu lịch sử vị trí / No location history stored</span></li>
            <li><i class="fas fa-history"></i> <span data-vi="Tự động xoá khi rời đi / Auto-delete when leaving" data-en="Auto-delete when leaving">Tự động xoá khi rời đi / Auto-delete when leaving</span></li>
        </ul>
        <div id="locationError" class="loc-error" style="display:none;"></div>
        <button id="btnAllowLocation" class="btn-loc-start">
            <i class="fas fa-location-arrow"></i> <span data-vi="BẮT ĐẦU TRẢI NGHIỆM / START EXPERIENCE" data-en="START EXPERIENCE">BẮT ĐẦU TRẢI NGHIỆM / START EXPERIENCE</span>
        </button>
        <p class="loc-privacy" data-vi="Bằng cách tiếp tục, bạn đồng ý với chính sách bảo mật của chúng tôi. / By continuing, you agree to our privacy policy." data-en="By continuing, you agree to our privacy policy.">Bằng cách tiếp tục, bạn đồng ý với chính sách bảo mật của chúng tôi. / By continuing, you agree to our privacy policy.</p>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     CSS
═══════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/customer.css">
<style>
/* ── Location Overlay ── */
.loc-overlay {
    position:fixed;inset:0;display:flex;align-items:center;justify-content:center;
    padding:20px;z-index:10000;
    background:linear-gradient(135deg,rgba(15,23,42,.96),rgba(30,41,59,.99));
    backdrop-filter:blur(18px);color:#fff;
}
.loc-card {
    background:#1e293b;padding:36px 28px;border-radius:28px;
    border:1px solid rgba(212,175,55,.3);max-width:400px;width:100%;
    box-shadow:0 30px 60px rgba(0,0,0,.5);text-align:center;
}
.loc-icon-ring {
    width:68px;height:68px;border-radius:50%;
    background:rgba(212,175,55,.1);border:1.5px solid var(--gold,#d4af37);
    color:var(--gold,#d4af37);font-size:1.8rem;
    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;
}
.loc-card h3 {
    font-family:'Playfair Display',serif;font-size:1.1rem;letter-spacing:2px;
    margin:0 0 4px;color:#fff;
}
.loc-sub { font-size:.7rem;letter-spacing:2px;color:var(--gold,#d4af37);margin:0 0 18px; }
.loc-dist-badge {
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(212,175,55,.15);color:var(--gold,#d4af37);
    padding:8px 18px;border-radius:50px;font-size:.85rem;font-weight:700;
    border:1px solid rgba(212,175,55,.3);margin-bottom:16px;
    animation:pulseSubtle 2s infinite;
}
.loc-dist-badge.err { background:rgba(239,68,68,.1);color:#f87171;border-color:rgba(239,68,68,.3); }
@keyframes pulseSubtle { 0%,100%{opacity:.8;transform:scale(1)} 50%{opacity:1;transform:scale(1.04)} }
.loc-benefits {
    list-style:none;padding:0;margin:16px 0 20px;text-align:left;
}
.loc-benefits li {
    font-size:.83rem;color:#cbd5e1;margin-bottom:10px;
    display:flex;align-items:center;gap:10px;
}
.loc-benefits i { color:var(--gold,#d4af37);font-size:.9rem; }
.loc-error {
    background:rgba(239,68,68,.1);color:#f87171;padding:10px 14px;
    border-radius:10px;border:1px solid rgba(239,68,68,.2);
    font-size:.82rem;margin-bottom:16px;text-align:left;
}
.loc-hint { font-size:.8rem;color:#94a3b8;margin-top:10px; }
.btn-loc-start {
    width:100%;background:linear-gradient(135deg,#d4af37,#b8860b);
    color:#fff;border:none;padding:15px;border-radius:14px;
    font-weight:800;font-size:.95rem;letter-spacing:1px;
    cursor:pointer;transition:all .3s;display:flex;align-items:center;
    justify-content:center;gap:10px;margin-bottom:12px;
}
.btn-loc-start:hover { transform:translateY(-2px);box-shadow:0 10px 24px rgba(212,175,55,.35); }
.loc-privacy { font-size:.68rem;color:#475569;margin:0; }

/* ── Menu type tab bar ── */
.type-tab-bar {
    display:flex;gap:6px;padding:10px 16px;overflow-x:auto;
    background:#fff;scrollbar-width:none;border-bottom:1px solid #f1f5f9;
}
.type-tab-bar::-webkit-scrollbar { display:none; }
.type-tab {
    white-space:nowrap;padding:7px 18px;border-radius:50px;
    border:1.5px solid #e2e8f0;background:#f8fafc;
    font-size:.75rem;font-weight:700;color:#64748b;
    cursor:pointer;transition:all .2s;flex-shrink:0;
}
.type-tab.active {
    background:var(--gold,#c5a059);color:#fff;
    border-color:var(--gold,#c5a059);
    box-shadow:0 3px 10px rgba(197,160,89,.3);
}

/* ── Item tags ── */
.item-tags { display:flex;flex-wrap:wrap;gap:4px;margin-top:5px; }
.item-tag {
    font-size:.6rem;font-weight:800;padding:2px 7px;border-radius:5px;color:#fff;
}
.item-tag.bestseller { background:#ef4444; }
.item-tag.new        { background:#8b5cf6; }
.item-tag.spicy      { background:#f97316; }
.item-tag.vegetarian { background:#16a34a; }
.item-tag.recommended{ background:#0ea5e9; }

/* ── Unavailable overlay ── */
.item-unavailable {
    opacity:.5;pointer-events:none;position:relative;
}
.item-unavailable::after {
    content:'Hết hàng';position:absolute;inset:0;display:flex;
    align-items:center;justify-content:center;
    background:rgba(255,255,255,.7);border-radius:inherit;
    font-weight:800;color:#94a3b8;font-size:.8rem;
}

/* ── Empty state ── */
.menu-empty-state {
    text-align:center;padding:3rem 1.5rem;color:#94a3b8;
}
.menu-empty-state i { font-size:3rem;opacity:.3;display:block;margin-bottom:1rem; }

/* ── Bill items (current order) ── */
.bill-items-container { max-height:40vh;overflow-y:auto; }
.bill-item { padding:12px 0;border-bottom:1px dashed #e2e8f0; }
.bill-item-main { display:flex;align-items:center;gap:10px;font-weight:600; }
.bill-qty { color:var(--gold-dark,#a68341);min-width:28px;font-size:.9rem; }
.bill-name { flex:1;font-size:.9rem; }
.bill-price { color:#0f172a;font-weight:700; }
.bill-item-status { font-size:.7rem;margin-top:3px;padding-left:38px;font-weight:600; }
.bill-item-status.confirmed { color:#10b981; }
.bill-item-status.pending   { color:#f59e0b; }
.bill-item-status.draft     { color:#94a3b8; }
.bill-summary {
    background:#f8fafc;padding:14px;border-radius:12px;
    border:1px solid #e2e8f0;margin-top:12px;
}
.bill-total-row { display:flex;justify-content:space-between;align-items:center; }
.bill-total-row span { color:#64748b;font-weight:600; }
.bill-total-row strong { font-size:1.3rem;color:var(--gold-dark,#a68341);font-weight:800; }

/* ── Glow payment button ── */
.glow-payment {
    background:var(--gold,#c5a059)!important;color:#fff!important;
    box-shadow:0 0 15px rgba(197,160,89,.7);
    animation:pulseGold 2s infinite;
}
@keyframes pulseGold {
    0%   { box-shadow:0 0 0 0 rgba(197,160,89,.7); }
    70%  { box-shadow:0 0 0 14px rgba(197,160,89,0); }
    100% { box-shadow:0 0 0 0 rgba(197,160,89,0); }
}

/* ── Misc utils ── */
.w-100{width:100%} .mb-2{margin-bottom:.5rem} .me-2{margin-right:.5rem}
.btn-gold {
    background:linear-gradient(135deg,var(--gold,#c5a059),var(--gold-dark,#a68341));
    color:#fff;border:none;padding:14px 20px;border-radius:12px;
    font-weight:700;letter-spacing:.5px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 4px 15px rgba(197,160,89,.3);transition:all .3s;
    font-family:inherit;
}
.btn-gold:active { transform:scale(.98); }
.btn-ghost {
    background:#fff;color:#64748b;border:1px solid #e2e8f0;
    padding:14px 20px;border-radius:12px;font-weight:600;
    cursor:pointer;display:flex;align-items:center;justify-content:center;
    font-family:inherit;transition:all .2s;
}
.btn-ghost:active { background:#f8fafc; }
</style>

<!-- ══════════════════════════════════════════════════════
     MAIN MENU WRAPPER
═══════════════════════════════════════════════════════ -->
<div class="customer-menu-wrapper" id="menuWrapper" style="display:none;">
    <script>
        // Dùng PHP render trực tiếp — CUSTOMER_CONFIG chưa khai báo ở đây
        (function(){
            var _tid = <?= (int)$table['id'] ?>;
            var _key = 'locationVerified_table_' + _tid;
            if (localStorage.getItem(_key) === 'true') {
                document.getElementById('menuWrapper').style.display = 'block';
            }

            // --- persistence Siêu bền vững ---
            var _serverVt = '<?= $visitorToken ?>';
            var _localVt = localStorage.getItem('qr_global_device_id') || localStorage.getItem('qr_vt_' + _tid);

            // Ưu tiên token mà Server trả về (vì Server có logic khôi phục hoặc sinh mới)
            if (_serverVt) {
                localStorage.setItem('qr_global_device_id', _serverVt);
                localStorage.setItem('qr_vt_' + _tid, _serverVt);
            } else if (_localVt) {
                // Nếu server k trả về (hiếm), khôi phục từ local
                document.cookie = 'qr_visitor_token=' + _localVt + '; path=/; max-age=31104000; SameSite=Lax';
            }
        })();
    </script>

    <!-- DEV MODE Banner -->
    <?php if (!empty($devMode)): ?>
    <div style="position:fixed;top:0;left:0;right:0;z-index:99999;background:linear-gradient(90deg,#7c3aed,#8b5cf6);color:#fff;text-align:center;padding:8px 16px;font-size:0.75rem;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;box-shadow:0 2px 10px rgba(124,58,237,0.4);">
        <i class="fas fa-wrench me-2"></i> DEV MODE — Kiểm tra vị trí đã tắt <i class="fas fa-code ms-2"></i>
    </div>
    <style>
        /* Adjust header padding for dev banner */
        .menu-header { padding-top: 40px !important; }
    </style>
    <?php endif; ?>

    <!-- Header - Compact -->
    <header class="menu-header">
        <div class="header-top">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="sidebar-toggle-btn-header" id="sidebarToggleBtnHeader" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="brand-logo">
                    <h1 class="playfair">AURORA</h1>
                    <span><?= $contextLabel ?></span>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <button id="langToggle" onclick="toggleLanguage()" class="lang-toggle-btn">
                    <i class="fas fa-globe me-1"></i><span id="langText">EN</span>
                </button>
                <div class="table-info">
                    <span class="table-label"><?= $isRoomService ? 'PHÒNG' : 'BÀN' ?></span>
                    <span class="table-number"><?= e($table['name']) ?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Type tab bar (chỉ sinh ra các menu_type thực sự có trong danh mục của bàn này) -->
    <?php
    $presentTypes = array_unique(array_column($activeCategories, 'menu_type'));
    $typeLabels = ['asia'=>'Món Á', 'europe'=>'Món Âu', 'alacarte'=>'Alacarte', 'other'=>'Đ.Uống & Khác'];
    $typeLabelsEn = ['asia'=>'Asian', 'europe'=>'European', 'alacarte'=>'Alacarte', 'other'=>'Beverages & Others'];
    // Chỉ hiển thị tab bar nếu có từ 2 type trở lên
    ?>
    <?php if (count($presentTypes) > 1): ?>
    <div class="type-tab-bar" id="typeTabBar">
        <button class="type-tab active" data-type="all"><span class="lang" data-vi="TẤT CẢ / ALL" data-en="ALL">TẤT CẢ / ALL</span></button>
        <?php foreach ($presentTypes as $tp): if (!isset($typeLabels[$tp])) continue; ?>
            <button class="type-tab" data-type="<?= $tp ?>">
                <span class="lang" data-vi="<?= strtoupper($typeLabels[$tp]) ?> / <?= strtoupper($typeLabelsEn[$tp]) ?>" data-en="<?= strtoupper($typeLabelsEn[$tp]) ?>"><?= strtoupper($typeLabels[$tp]) ?> / <?= strtoupper($typeLabelsEn[$tp]) ?></span>
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Category nav sticky -->
    <nav class="category-nav">
        <div class="category-nav-inner">
            <a href="javascript:void(0)" class="cat-pill active" data-category="all">
                <span class="lang" data-vi="Tất cả / All" data-en="All">Tất cả / All</span>
            </a>
            <?php foreach ($activeCategories as $cat): ?>
                <a href="#cat-<?= $cat['id'] ?>" class="cat-pill"
                   data-category="<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                    <span class="lang-vi"><?= e($cat['name']) ?></span>
                    <?php if (!empty($cat['name_en'])): ?>
                        <span class="lang-en" style="display:none;"><?= e($cat['name_en']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Search -->
    <div class="menu-search-container">
        <div class="menu-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="Tìm món (tên Việt / English)...">
            <button id="btnClearSearch" style="display:none;background:none;border:none;color:#94a3b8;cursor:pointer;padding:0 4px;" onclick="clearMenuSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <style>
    /* ════════════════════════════════════════════════════
       UNIFIED TYPOGRAPHY SYSTEM
       Font: Outfit (Primary) + Playfair Display (Accents)
       ════════════════════════════════════════════════════ */
    
    /* Language toggle styles */
    .lang-toggle-btn:hover {
        background: var(--gold) !important;
        color: #fff !important;
    }
    
    /* Typography Classes */
    .font-primary { font-family: 'Outfit', sans-serif; }
    .font-display { font-family: 'Playfair Display', serif; }
    
    /* ════════════════════════════════════════════════════
       SIDEBAR LEFT NAVIGATION
       ════════════════════════════════════════════════════ */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .sidebar-left {
        position: fixed;
        top: 0;
        left: -280px;
        width: 280px;
        height: 100vh;
        background: linear-gradient(135deg, #fff, #f8fafc);
        z-index: 999;
        box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-left.active {
        transform: translateX(280px);
    }
    
    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
    }
    
    .sidebar-header h3 {
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--gold);
        letter-spacing: 3px;
        text-transform: uppercase;
    }
    
    .sidebar-close {
        background: var(--bg);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-med);
    }
    
    .sidebar-close:hover {
        background: var(--gold-light);
        color: var(--gold);
    }
    
    .sidebar-nav {
        flex: 1;
        padding: 16px 0;
        overflow-y: auto;
    }
    
    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 24px;
        color: var(--text-dark);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        font-family: 'Outfit', sans-serif;
        transition: all 0.2s;
        position: relative;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        letter-spacing: 0.3px;
    }
    
    .sidebar-item:hover {
        background: var(--gold-light);
        color: var(--gold);
    }
    
    .sidebar-item i {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
        color: var(--gold);
    }
    
    .sidebar-item.has-items {
        background: rgba(197, 160, 89, 0.08);
    }
    
    .sidebar-badge {
        position: absolute;
        right: 24px;
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulseBadge 1.5s infinite;
    }
    
    @keyframes pulseBadge {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
    }
    
    .sidebar-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border-light);
    }
    
    .lang-toggle-sidebar {
        background: var(--gold);
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .lang-toggle-sidebar:hover {
        background: var(--gold-dark);
    }
    
    .sidebar-toggle-btn-header {
        background: var(--gold-light);
        border: 1px solid rgba(197, 160, 89, 0.3);
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--gold);
        flex-shrink: 0;
    }
    
    .sidebar-toggle-btn-header i {
        font-size: 1.2rem;
    }
    
    .sidebar-toggle-btn-header:hover {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
        transform: scale(1.05);
    }
    
    .sidebar-toggle-btn-header:active {
        transform: scale(0.95);
    }
    
    /* Responsive */
    @media (max-width: 480px) {
        .sidebar-left {
            width: 260px;
            left: -260px;
        }
        
        .sidebar-left.active {
            transform: translateX(260px);
        }
        
        .sidebar-toggle-btn-header {
            width: 38px;
            height: 38px;
        }
        
        .sidebar-toggle-btn-header i {
            font-size: 1.1rem;
        }
    }
    </style>

    <!-- Menu Sections -->
    <main class="menu-sections" id="menuSections">
        <?php foreach ($activeCategories as $cat): ?>
            <?php if (!isset($grouped[$cat['id']])) continue; ?>
            <section class="menu-section" id="cat-<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                <div class="section-header">
                    <h2 class="section-title"><?= e($cat['name']) ?></h2>
                    <?php if (!empty($cat['name_en'])): ?>
                        <span class="section-title-en"><?= e($cat['name_en']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="menu-list">
                    <?php foreach ($grouped[$cat['id']] as $item):
                        $isUnavailable = !$item['is_available'];
                        $tags = array_filter(array_map('trim', explode(',', $item['tags'] ?? '')));
                    ?>
                        <div class="menu-item-card<?= $isUnavailable ? ' item-unavailable' : '' ?>"
                             data-id="<?= $item['id'] ?>"
                             data-name="<?= strtolower(e($item['name'])) ?>"
                             data-name-en="<?= strtolower(e($item['name_en'] ?? '')) ?>"
                             data-price="<?= $item['price'] ?>"
                             data-type="<?= $cat['menu_type'] ?>"
                             data-options="<?= e($item['note_options'] ?? '') ?>"
                             data-options-en="<?= e($item['note_options_en'] ?? '') ?>"
                             onclick="<?= $isUnavailable ? '' : 'showItemDetail(' . e(json_encode($item)) . ')' ?>">

                            <div class="item-img-box">
                                <?php if ($item['image']): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>"
                                         alt="<?= e($item['name']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="item-placeholder"><i class="fas fa-utensils"></i></div>
                                <?php endif; ?>
                                <?php if (in_array('bestseller', $tags)): ?>
                                    <span class="item-badge bestseller">HOT</span>
                                <?php elseif (in_array('new', $tags)): ?>
                                    <span class="item-badge" style="background:#8b5cf6;">NEW</span>
                                <?php endif; ?>
                            </div>

                            <div class="item-info">
                                <div class="item-main-row">
                                    <h3 class="item-name"><?= e($item['name']) ?></h3>
                                    <span class="item-price"><?= formatPrice($item['price']) ?></span>
                                </div>
                                <?php if (!empty($item['name_en'])): ?>
                                    <div class="item-name-en"><?= e($item['name_en']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="item-desc"><?= e($item['description']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($tags)): ?>
                                <div class="item-tags">
                                    <?php foreach ($tags as $tag): if (!in_array($tag, ['bestseller','new','spicy','vegetarian','recommended'])) continue; ?>
                                        <span class="item-tag <?= $tag ?>"><?= ucfirst($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <div class="item-footer">
                                    <?php if (!$isUnavailable): ?>
                                    <button class="btn-add-circle"
                                            onclick="event.stopPropagation(); quickAdd(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>, '<?= e($item['name_en'] ?? '') ?>')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <?php else: ?>
                                    <span style="font-size:.72rem;color:#94a3b8;font-weight:700;">Hết hàng</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <?php if (empty($activeCategories)): ?>
        <div class="menu-empty-state">
            <i class="fas fa-utensils"></i>
            <p style="font-weight:700;font-size:1rem;">Chưa có thực đơn</p>
            <p style="font-size:.85rem;">Vui lòng liên hệ nhân viên để được hỗ trợ</p>
        </div>
        <?php endif; ?>

        <div id="searchNoResult" class="menu-empty-state" style="display:none;">
            <i class="fas fa-search"></i>
            <p style="font-weight:700;font-size:1rem;">Không tìm thấy món phù hợp</p>
            <button onclick="clearMenuSearch()" style="background:none;border:1.5px solid #e2e8f0;border-radius:20px;padding:8px 20px;cursor:pointer;font-weight:600;color:#64748b;margin-top:8px;">
                Xoá tìm kiếm
            </button>
        </div>
    </main>
</div>

    <!-- ── Sidebar Left Navigation ── -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar-left" id="sidebarLeft">
        <div class="sidebar-header">
            <h3 class="playfair">AURORA</h3>
            <button class="sidebar-close" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <nav class="sidebar-nav">
            <a href="javascript:void(0)" class="sidebar-item" onclick="callWaiter('support'); toggleSidebar()">
                <i class="fas fa-<?= $isRoomService ? 'concierge-bell' : 'hand-paper' ?>"></i>
                <span class="lang" data-vi="Gọi nhân viên / <?= $isRoomService ? 'Call Reception' : 'Call Waiter' ?>" data-en="<?= $isRoomService ? 'Call Reception' : 'Call Waiter' ?>">Gọi nhân viên / <?= $isRoomService ? 'Call Reception' : 'Call Waiter' ?></span>
            </a>
            <a href="javascript:void(0)" class="sidebar-item <?= $hasItems ? 'has-items' : '' ?>" onclick="<?= $hasItems ? 'showBillTam()' : "callWaiter('payment')" ?>; toggleSidebar()">
                <i class="fas fa-file-invoice-dollar"></i>
                <span class="lang" data-vi="<?= $hasItems ? 'Hoá đơn / Bill' : 'Thanh toán / Payment' ?>" data-en="<?= $hasItems ? 'Bill' : 'Payment' ?>"><?= $hasItems ? 'Hoá đơn / Bill' : 'Thanh toán / Payment' ?></span>
                <?php if ($hasItems): ?>
                    <span class="sidebar-badge"></span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/qr/landing" class="sidebar-item">
                <i class="fas fa-history"></i>
                <span class="lang" data-vi="Lịch sử / History" data-en="History">Lịch sử / History</span>
            </a>
            <a href="javascript:void(0)" class="sidebar-item" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span class="lang" data-vi="Làm mới / Refresh" data-en="Refresh">Làm mới / Refresh</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button id="langToggleSidebar" onclick="toggleLanguage()" class="lang-toggle-sidebar">
                <i class="fas fa-globe"></i>
                <span id="langTextSidebar">EN</span>
            </button>
        </div>
    </aside>
    

<!-- ── Floating Cart Bar ── -->
<div id="cartBar" class="cart-bar hidden">
    <div class="cart-bar-content">
        <div class="cart-icon-box">
            <i class="fas fa-shopping-basket"></i>
            <span class="cart-badge" id="cartCount">0</span>
        </div>
        <div class="cart-info">
            <span class="cart-label">Giỏ hàng của bạn</span>
            <span class="cart-total" id="cartTotal">0₫</span>
        </div>
        <button class="btn-view-cart" onclick="toggleCartModal()">
            XEM GIỎ <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<!-- ── Cart Modal ── -->
<div id="cartModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-cart me-2"></i> <span class="lang" data-vi="Chi tiết đơn hàng / Order Details" data-en="Order Details">Chi tiết đơn hàng / Order Details</span></h3>
            <button class="modal-close" onclick="toggleCartModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="cartItemsList" class="cart-items-container"></div>
            <div class="order-notes-box mt-3" style="margin-top:1rem;">
                <label class="lang" data-vi="GHI CHÚ ĐƠN HÀNG / ORDER NOTES" data-en="ORDER NOTES" style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">GHI CHÚ ĐƠN HÀNG / ORDER NOTES</label>
                <textarea id="orderNotes" placeholder="VD: Không lấy hành, ít cay..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="total-summary">
                <span class="lang" data-vi="Tổng cộng / Total" data-en="Total">Tổng cộng / Total</span>
                <strong id="modalCartTotal">0₫</strong>
            </div>
            <button class="btn-submit-order" id="btnSubmitOrder" onclick="submitOrder()">
                <i class="fas fa-paper-plane me-2"></i> <span class="lang" data-vi="XÁC NHẬN ĐẶT MÓN / CONFIRM ORDER" data-en="CONFIRM ORDER">XÁC NHẬN ĐẶT MÓN / CONFIRM ORDER</span>
            </button>
        </div>
    </div>
</div>

<!-- ── Item Detail Modal ── -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header border-0" style="border:none;position:relative;">
            <button class="modal-close-circle" onclick="closeItemDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="item-detail-img" id="detailImg" style="width:100%;height:240px;background-size:cover;background-position:center;position:relative;"></div>
        <div class="modal-body">
            <div style="margin-bottom:1rem;">
                <h2 id="detailName" class="playfair" style="margin:0 0 4px;font-size:1.4rem;font-weight:800;"></h2>
                <div id="detailNameEn" class="item-name-en"></div>
                <div id="detailPrice" class="item-price" style="font-size:1.2rem;font-weight:800;color:var(--gold-dark);"></div>
                <p id="detailDesc" class="item-desc" style="margin-top:8px;font-size:.875rem;color:#64748b;line-height:1.5;"></p>
            </div>
            <div id="detailOptsWrap" style="display:none;margin-bottom:1.25rem;">
                <label class="lang" data-vi="Tuỳ chọn nhanh / Quick Options" data-en="Quick Options" style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;display:block;">
                    Tuỳ chọn nhanh / Quick Options
                </label>
                <div id="detailOptsContainer" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
            </div>
            <div class="order-controls">
                <div class="qty-control-premium">
                    <button onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty">1</span>
                    <button onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="note-input-box">
                    <i class="fas fa-edit"></i>
                    <input type="text" id="detailNote" placeholder="Ghi chú thêm (No onion, less spicy...)">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-submit-order w-100" id="btnAddOrder" onclick="addFromDetail()">
                <i class="fas fa-cart-plus me-2"></i> <span class="lang" data-vi="THÊM VÀO ĐƠN HÀNG / ADD TO ORDER" data-en="ADD TO ORDER">THÊM VÀO ĐƠN HÀNG / ADD TO ORDER</span>
            </button>
        </div>
    </div>
</div>

<!-- ── Bill Modal ── -->
<div id="billTamModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar me-2"></i> <span class="lang" data-vi="Hoá đơn tạm tính / Preliminary Bill" data-en="Preliminary Bill">Hoá đơn tạm tính / Preliminary Bill</span></h3>
            <button class="modal-close" onclick="closeBillTam()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="bill-items-container">
                <?php if ($hasItems): ?>
                    <?php foreach ($orderItems as $oi):
                        if ($oi['status'] === 'cancelled') continue; ?>
                        <div class="bill-item">
                            <div class="bill-item-main">
                                <span class="bill-qty"><?= $oi['quantity'] ?>x</span>
                                <span class="bill-name lang" data-vi="<?= e($oi['item_name']) ?>" data-en="<?= e(!empty($oi['item_name_en']) ? $oi['item_name_en'] : $oi['item_name']) ?>"><?= e($currentLang === 'en' && !empty($oi['item_name_en']) ? $oi['item_name_en'] : $oi['item_name']) ?></span>
                                <span class="bill-price"><?= formatPrice($oi['item_price'] * $oi['quantity']) ?></span>
                            </div>
                            <?php if (!empty($oi['note'])): ?>
                                <div style="font-size:.7rem;color:#94a3b8;padding-left:36px;margin-top:2px;">
                                    <i class="fas fa-pen" style="font-size:.6rem;"></i> <?= e($oi['note']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="bill-item-status <?= $oi['status'] ?>">
                                <?php
                                $statusTxt = ['confirmed'=>'✅ Đã xác nhận','pending'=>'⏳ Chờ xác nhận','draft'=>'📝 Chờ xác nhận'];
                                $statusTxtEn = ['confirmed'=>'✅ Confirmed','pending'=>'⏳ Pending','draft'=>'📝 Draft'];
                                echo $currentLang === 'en' ? ($statusTxtEn[$oi['status']] ?? $oi['status']) : ($statusTxt[$oi['status']] ?? $oi['status']);
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="bill-summary">
                        <div class="bill-total-row">
                            <span class="lang" data-vi="Tổng tiền món / Subtotal" data-en="Subtotal">Tổng tiền món / Subtotal</span>
                            <strong><?= formatPrice($orderTotal) ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="menu-empty-state">
                        <i class="fas fa-receipt"></i>
                        <p class="lang" data-vi="Bàn chưa có món nào được gọi. / No items ordered yet." data-en="No items ordered yet.">Bàn chưa có món nào được gọi. / No items ordered yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;flex-direction:column;gap:.5rem;">
            <button class="btn-gold w-100" onclick="callWaiter('payment')">
                <i class="fas fa-hand-holding-usd me-2"></i> <span class="lang" data-vi="YÊU CẦU THANH TOÁN / REQUEST PAYMENT" data-en="REQUEST PAYMENT">YÊU CẦU THANH TOÁN / REQUEST PAYMENT</span>
            </button>
            <button class="btn-ghost w-100" onclick="closeBillTam()">
                <span class="lang" data-vi="TIẾP TỤC ĐẶT MÓN / CONTINUE ORDERING" data-en="CONTINUE ORDERING">TIẾP TỤC ĐẶT MÓN / CONTINUE ORDERING</span>
            </button>
        </div>
    </div>
</div>

<!-- opt-chip style -->
<style>
.opt-chip-premium {
    padding:6px 14px;background:#f8fafc;color:#475569;border-radius:50px;
    font-size:.8rem;font-weight:600;cursor:pointer;transition:all .2s;
    border:1.5px solid #e2e8f0;display:inline-flex;align-items:center;gap:6px;
}
.opt-chip-premium.active {
    background:rgba(197,160,89,.12);color:var(--gold-dark,#a68341);
    border-color:var(--gold,#c5a059);transform:scale(1.03);
}
.modal-close-circle {
    position:absolute;top:12px;right:12px;width:34px;height:34px;
    border-radius:50%;background:rgba(0,0,0,.45);color:#fff;border:none;
    display:flex;align-items:center;justify-content:center;z-index:10;cursor:pointer;
}
</style>

<!-- Config & inline utilities -->
<script>
const CUSTOMER_CONFIG = {
    tableId:           <?= $table['id'] ?>,
    tableName:         '<?= e($table['name']) ?>',
    isRoomService:     <?= $isRoomService ? 'true' : 'false' ?>,
    baseUrl:           '<?= BASE_URL ?>',
    isIT:              <?= (\Auth::isIT() ?? false) ? 'true' : 'false' ?>,
    hasItems:          <?= $hasItems ? 'true' : 'false' ?>,
    restaurantCoords:  { lat: <?= RESTAURANT_LAT ?>, lng: <?= RESTAURANT_LNG ?> },
    maxDistance:       <?= MAX_ORDER_DISTANCE ?>,
    showBill:          <?= isset($_GET['show_bill']) ? 'true' : 'false' ?>,
    devMode:           <?= !empty($devMode) ? 'true' : 'false' ?>
};

/* ── Type tab filter ── */
document.querySelectorAll('.type-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.type-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const type = btn.dataset.type;
        document.querySelectorAll('.menu-section').forEach(sec => {
            sec.style.display = (type === 'all' || sec.dataset.type === type) ? '' : 'none';
        });
        document.querySelectorAll('.cat-pill[data-type]').forEach(pill => {
            pill.style.display = (type === 'all' || pill.dataset.type === type) ? '' : 'none';
        });
        // scroll to top of menu
        const ms = document.getElementById('menuSections');
        if (ms) ms.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

/* ── Search with clear button ── */
const _searchEl = document.getElementById('menuSearch');
const _clearBtn = document.getElementById('btnClearSearch');
if (_searchEl) {
    _searchEl.addEventListener('input', _filterMenu);
    function _filterMenu() {
        const q = _searchEl.value.trim().toLowerCase();
        _clearBtn.style.display = q ? '' : 'none';
        let anyVisible = false;
        document.querySelectorAll('.menu-item-card').forEach(card => {
            const match = card.dataset.name.includes(q) || card.dataset.nameEn.includes(q);
            card.style.display = match ? 'flex' : 'none';
            if (match) anyVisible = true;
        });
        document.querySelectorAll('.menu-section').forEach(sec => {
            const hasVisible = [...sec.querySelectorAll('.menu-item-card')].some(c => c.style.display !== 'none');
            sec.style.display = hasVisible ? '' : 'none';
        });
        document.getElementById('searchNoResult').style.display = (!anyVisible && q) ? '' : 'none';
    }
}
function clearMenuSearch() {
    if (_searchEl) { _searchEl.value = ''; _filterMenu(); }
}

/* ── Sidebar Toggle ── */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarLeft');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    } else {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/* ── FAB Toggle (legacy - kept for compatibility) ── */
let fabOpen = false;
function toggleFab() {
    fabOpen = !fabOpen;
    const menu = document.getElementById('fabMenu');
    const icon = document.getElementById('fabIcon');
    const main = document.getElementById('fabMain');
    
    if (fabOpen) {
        menu.classList.add('show');
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
        main.classList.add('active');
    } else {
        menu.classList.remove('show');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
        main.classList.remove('active');
    }
}

/* ── Bill modal ── */
function showBillTam() {
    toggleSidebar();
    document.getElementById('billTamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeBillTam() {
    document.getElementById('billTamModal').classList.add('hidden');
    document.body.style.overflow = '';
}

/* ── Language Toggle VI/EN ── */
let currentLang = localStorage.getItem('aurora_lang') || 'vi';

function toggleLanguage() {
    currentLang = currentLang === 'vi' ? 'en' : 'vi';
    localStorage.setItem('aurora_lang', currentLang);
    document.cookie = "aurora_lang=" + currentLang + "; path=/; max-age=31536000; SameSite=Lax";
    applyLanguage(currentLang);
}

function applyLanguage(lang) {
    document.documentElement.lang = lang;
    // Update toggle button text
    const langText = document.getElementById('langText');
    if (langText) langText.textContent = lang === 'vi' ? 'EN' : 'VI';
    
    // Update elements with class="lang" and data-vi/data-en attributes
    document.querySelectorAll('.lang').forEach(el => {
        const text = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (text) el.textContent = text;
    });
    
    // Update category pills
    document.querySelectorAll('.cat-pill').forEach(pill => {
        const viEl = pill.querySelector('.lang-vi');
        const enEl = pill.querySelector('.lang-en');
        if (viEl && enEl) {
            viEl.style.display = lang === 'vi' ? '' : 'none';
            enEl.style.display = lang === 'en' ? '' : 'none';
        }
    });
    
    // Update section titles
    document.querySelectorAll('.section-header').forEach(header => {
        const viTitle = header.querySelector('.section-title');
        const enTitle = header.querySelector('.section-title-en');
        if (viTitle && enTitle) {
            viTitle.style.display = '';
            enTitle.style.display = lang === 'en' ? '' : 'none';
        }
    });
    
    // Update item names
    document.querySelectorAll('.menu-item-card').forEach(card => {
        const nameEl = card.querySelector('.item-name');
        const nameEnEl = card.querySelector('.item-name-en');
        if (nameEnEl) {
            if (lang === 'en' && nameEnEl.textContent.trim()) {
                nameEl.style.display = 'none';
                nameEnEl.style.display = 'block';
                nameEnEl.style.fontWeight = '700';
                nameEnEl.style.fontSize = '0.9rem';
                nameEnEl.style.color = 'var(--text-dark)';
            } else {
                nameEl.style.display = '';
                nameEnEl.style.display = '';
                nameEnEl.style.fontWeight = '';
                nameEnEl.style.fontSize = '';
                nameEnEl.style.color = '';
            }
        }
    });
    
    // Update context banner text
    const ctxText = document.querySelector('.ctx-text');
    if (ctxText) {
        if (lang === 'en') {
            ctxText.textContent = ctxText.getAttribute('data-en') || 'Room service menu — Order will be delivered to your room';
        } else {
            const isRoom = <?= $isRoomService ? 'true' : 'false' ?>;
            ctxText.textContent = isRoom ? 'Thực đơn phục vụ tại phòng — Đặt món sẽ được mang đến tận nơi' : 'Thực đơn nhà hàng — Đặt món ngay tại bàn và chờ phục vụ';
        }
    }
    
    // Update search placeholder
    const searchEl = document.getElementById('menuSearch');
    if (searchEl) {
        searchEl.placeholder = lang === 'vi' ? 'Tìm món (tên Việt / English)...' : 'Search for dishes (Vietnamese / English)...';
    }
    
    // Update cart label
    const cartLabel = document.querySelector('.cart-label');
    if (cartLabel) {
        cartLabel.textContent = lang === 'vi' ? 'Giỏ hàng của bạn' : 'Your cart';
    }
    
    // Update view cart button
    const viewCartBtn = document.querySelector('.btn-view-cart');
    if (viewCartBtn) {
        viewCartBtn.innerHTML = lang === 'vi' ? 'XEM GIỎ <i class="fas fa-chevron-right"></i>' : 'VIEW <i class="fas fa-chevron-right"></i>';
    }
    
    // Update FAB labels
    document.querySelectorAll('.fab-label').forEach(label => {
        const text = lang === 'vi' ? label.getAttribute('data-vi') : label.getAttribute('data-en');
        if (text) label.textContent = text;
    });
    
    // Update location overlay
    document.querySelectorAll('#locationOverlay .loc-title, #locationOverlay h3').forEach(el => {
        const text = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (text) el.textContent = text;
    });
    
    document.querySelectorAll('#locationOverlay .loc-desc').forEach(el => {
        const text = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (text) el.textContent = text;
    });
    
    document.querySelectorAll('#locationOverlay .loc-benefits span').forEach(el => {
        const text = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (text) el.textContent = text;
    });
    
    document.querySelectorAll('#locationOverlay .loc-privacy').forEach(el => {
        const text = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (text) el.textContent = text;
    });
    
    // Update frozen overlay
    const frozenTitle = document.querySelector('#frozenOverlay h3');
    if (frozenTitle) {
        frozenTitle.textContent = lang === 'vi' ? 'BẠN ĐÃ RỜI KHỎI KHU VỰC' : 'YOU HAVE LEFT THE AREA';
    }
    
    const frozenSub = document.querySelector('#frozenOverlay .loc-sub');
    if (frozenSub) {
        frozenSub.textContent = lang === 'vi' ? 'Thực đơn tạm thời bị khoá để bảo mật đơn hàng' : 'Menu is temporarily locked for order security';
    }
    
    const frozenHint = document.querySelector('#frozenOverlay .loc-hint');
    if (frozenHint) {
        frozenHint.textContent = lang === 'vi' ? 'Vui lòng quay lại khu vực để tiếp tục' : 'Please return to the area to continue';
    }
    
    // Update cart modal empty state
    const cartEmptyText = document.querySelector('#cartItemsList .text-muted');
    if (cartEmptyText) {
        cartEmptyText.textContent = lang === 'vi' ? 'Giỏ hàng đang trống.' : 'Your cart is empty.';
    }
    
    // Update item detail modal
    const detailDescDefault = document.getElementById('detailDesc');
    if (detailDescDefault && !currentItem) {
        detailDescDefault.textContent = lang === 'vi' ? 'Không có mô tả cho món ăn này.' : 'No description for this item.';
    }
    
    const notePlaceholder = document.getElementById('detailNote');
    if (notePlaceholder) {
        notePlaceholder.placeholder = lang === 'vi' ? 'Ghi chú thêm (No onion, less spicy...)' : 'Additional notes (No onion, less spicy...)';
    }
    
    const orderNotesLabel = document.querySelector('.order-notes-box label');
    if (orderNotesLabel) {
        orderNotesLabel.textContent = lang === 'vi' ? 'GHI CHÚ ĐƠN HÀNG' : 'ORDER NOTES';
    }
    
    const orderNotesTextarea = document.getElementById('orderNotes');
    if (orderNotesTextarea) {
        orderNotesTextarea.placeholder = lang === 'vi' ? 'VD: Không lấy hành, ít cay...' : 'e.g., No onion, less spicy...';
    }
}

// Apply language on page load
(function initLanguage() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            applyLanguage(currentLang);
        });
    } else {
        applyLanguage(currentLang);
    }
})();
</script>
<script src="<?= BASE_URL ?>/public/js/menu/customer.js?v=<?= time() ?>" defer></script>
