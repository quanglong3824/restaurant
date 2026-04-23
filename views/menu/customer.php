<?php
// views/menu/customer.php — Customer Digital Menu (Premium Redesign)
// Variables from controller: $table, $categories, $menuItems, $orderItems, $token, $visitorToken, $devMode

$currentLang = $_COOKIE['aurora_lang'] ?? 'vi';
$isEnglish = $currentLang === 'en';

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

// Nhóm menuItems theo category_id
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
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#c5a059">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= e($pageTitle ?? 'Menu') ?> — AURORA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- QRCode JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/customer.css">
    
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<!-- Location Check Overlay -->
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
        <div class="loc-icon-ring"><i class="fas fa-shield-alt"></i></div>
        <h3 class="loc-title"><?= $isEnglish ? 'CONFIRM YOUR PRESENCE' : 'XÁC NHẬN HIỆN DIỆN' ?></h3>
        <p class="loc-sub">AURORA HOTEL PLAZA</p>
        <div id="liveDistance" class="loc-dist-badge" style="display:none;">
            <i class="fas fa-map-marker-alt"></i> <span id="distVal">...</span>m
        </div>
        <p class="loc-desc"><?= $isEnglish ? 'For order security, please confirm your location.' : 'Để bảo mật đơn hàng, vui lòng xác nhận vị trí của bạn.' ?></p>
        <ul class="loc-benefits">
            <li><i class="fas fa-check-circle"></i> <span><?= $isEnglish ? 'Instant order confirmation' : 'Đơn hàng xác nhận ngay' ?></span></li>
            <li><i class="fas fa-lock"></i> <span><?= $isEnglish ? 'No location history stored' : 'Không lưu lịch sử vị trí' ?></span></li>
            <li><i class="fas fa-history"></i> <span><?= $isEnglish ? 'Auto-delete when leaving' : 'Tự động xoá khi rời đi' ?></span></li>
        </ul>
        <div id="locationError" class="loc-error" style="display:none;"></div>
        <button id="btnAllowLocation" class="btn-loc-start">
            <i class="fas fa-location-arrow"></i> <?= $isEnglish ? 'START EXPERIENCE' : 'BẮT ĐẦU TRẢI NGHIỆM' ?>
        </button>
        <p class="loc-privacy"><?= $isEnglish ? 'By continuing, you agree to our privacy policy.' : 'Bằng cách tiếp tục, bạn đồng ý với chính sách bảo mật.' ?></p>
    </div>
</div>

<!-- Frozen Overlay (out of range) -->
<div id="frozenOverlay" class="loc-overlay" style="display:none;">
    <div class="loc-card loc-card-error">
        <div class="loc-icon-ring loc-icon-error">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3 class="loc-title-error"><?= $isEnglish ? 'YOU HAVE LEFT THE AREA' : 'BẠN ĐÃ RỜI KHỎI KHU VỰC' ?></h3>
        <p class="loc-sub"><?= $isEnglish ? 'Menu is temporarily locked' : 'Thực đơn tạm thời bị khoá' ?></p>
        <div class="loc-dist-badge err">
            <i class="fas fa-walking"></i> <span id="frozenDistVal">...</span>m
        </div>
        <p class="loc-hint"><?= $isEnglish ? 'Please return to continue' : 'Vui lòng quay lại để tiếp tục' ?></p>
    </div>
</div>

<!-- Main Menu Wrapper -->
<div class="customer-menu-wrapper" id="menuWrapper" style="display:none;">
    <script>
        (function(){
            var _tid = <?= (int)$table['id'] ?>;
            var _key = 'locationVerified_table_' + _tid;
            if (localStorage.getItem(_key) === 'true') {
                document.getElementById('menuWrapper').style.display = 'block';
            }
            var _serverVt = '<?= $visitorToken ?>';
            var _localVt = localStorage.getItem('qr_global_device_id') || localStorage.getItem('qr_vt_' + _tid);
            if (_serverVt) {
                localStorage.setItem('qr_global_device_id', _serverVt);
                localStorage.setItem('qr_vt_' + _tid, _serverVt);
            } else if (_localVt) {
                document.cookie = 'qr_visitor_token=' + _localVt + '; path=/; max-age=31104000; SameSite=Lax';
            }
        })();
    </script>

    <!-- Header -->
    <header class="menu-header">
        <div class="header-top">
            <div class="brand-logo">
                <h1 class="playfair">AURORA</h1>
                <span><?= $contextLabel ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <button id="langToggle" onclick="toggleLanguage()" class="lang-toggle-btn">
                    <i class="fas fa-globe me-1"></i><span id="langText"><?= $isEnglish ? 'VI' : 'EN' ?></span>
                </button>
                <div class="table-info">
                    <span class="table-label"><?= $isRoomService ? ($isEnglish ? 'ROOM' : 'PHÒNG') : ($isEnglish ? 'TABLE' : 'BÀN') ?></span>
                    <span class="table-number"><?= e($table['name']) ?></span>
                </div>
            </div>
        </div>

        <!-- Context Banner -->
        <div class="ctx-banner" style="color:<?= $contextColor ?>;border-color:<?= $contextColor ?>33;">
            <i class="fas <?= $contextIcon ?>"></i>
            <span class="ctx-text"><?= $isRoomService 
                ? ($isEnglish ? 'Room service — Orders delivered to your room' : 'Phục vụ tại phòng — Món ăn mang đến tận nơi')
                : ($isEnglish ? 'Restaurant — Order at your table' : 'Nhà hàng — Đặt món ngay tại bàn') 
            ?></span>
        </div>
    </header>

    <!-- Type Tab Bar -->
    <?php
    $presentTypes = array_unique(array_column($activeCategories, 'menu_type'));
    $typeLabels = ['asia'=>'Món Á', 'europe'=>'Món Âu', 'alacarte'=>'Alacarte', 'other'=>$isEnglish ? 'Beverages' : 'Đ.Uống'];
    $typeLabelsEn = ['asia'=>'Asian', 'europe'=>'European', 'alacarte'=>'Alacarte', 'other'=>'Beverages'];
    ?>
    <?php if (count($presentTypes) > 1): ?>
    <div class="type-tab-bar" id="typeTabBar">
        <button class="type-tab active" data-type="all"><?= $isEnglish ? 'ALL' : 'TẤT CẢ' ?></button>
        <?php foreach ($presentTypes as $tp): if (!isset($typeLabels[$tp])) continue; ?>
            <button class="type-tab" data-type="<?= $tp ?>">
                <?= $isEnglish ? strtoupper($typeLabelsEn[$tp]) : strtoupper($typeLabels[$tp]) ?>
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Category Nav -->
    <nav class="category-nav">
        <div class="category-nav-inner">
            <a href="javascript:void(0)" class="cat-pill active" data-category="all">
                <?= $isEnglish ? 'All' : 'Tất cả' ?>
            </a>
            <?php foreach ($activeCategories as $cat): ?>
                <a href="#cat-<?= $cat['id'] ?>" class="cat-pill"
                   data-category="<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                    <?php if ($isEnglish && !empty($cat['name_en'])): ?>
                        <?= e($cat['name_en']) ?>
                    <?php else: ?>
                        <?= e($cat['name']) ?>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Search -->
    <div class="menu-search-container">
        <div class="menu-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="menuSearch" placeholder="<?= $isEnglish ? 'Search dishes...' : 'Tìm món ăn...' ?>">
            <button id="btnClearSearch" style="display:none;background:none;border:none;color:#94a3b8;cursor:pointer;padding:0 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Menu Sections -->
    <main id="menuSections">
        <?php foreach ($activeCategories as $cat): ?>
            <?php if (!isset($grouped[$cat['id']])) continue; ?>
            <section class="menu-section" id="cat-<?= $cat['id'] ?>" data-type="<?= $cat['menu_type'] ?>">
                <div class="section-header">
                    <h2 class="section-title playfair"><?= e($cat['name']) ?></h2>
                    <?php if ($isEnglish && !empty($cat['name_en'])): ?>
                        <span class="section-title-en"><?= e($cat['name_en']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="menu-list">
                    <?php foreach ($grouped[$cat['id']] as $item):
                        $isUnavailable = !$item['is_available'];
                        $tags = array_filter(array_map('trim', explode(',', $item['tags'] ?? '')));
                        $itemName = $isEnglish && !empty($item['name_en']) ? $item['name_en'] : $item['name'];
                        $itemDesc = $isEnglish && !empty($item['description_en']) ? $item['description_en'] : $item['description'];
                    ?>
                        <div class="menu-item-card<?= $isUnavailable ? ' item-unavailable' : '' ?>"
                             data-id="<?= $item['id'] ?>"
                             data-name="<?= strtolower(e($item['name'])) ?>"
                             data-name-en="<?= strtolower(e($item['name_en'] ?? '')) ?>"
                             data-price="<?= $item['price'] ?>"
                             data-type="<?= $cat['menu_type'] ?>"
                             data-unavailable-text="<?= $isEnglish ? 'UNAVAILABLE' : 'HẾT HÀNG' ?>"
                             onclick="<?= $isUnavailable ? '' : 'showItemDetail(' . e(json_encode($item)) . ')' ?>">

                            <div class="item-img-box">
                                <?php if ($item['image']): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt="<?= e($itemName) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="item-placeholder"><i class="fas fa-utensils"></i></div>
                                <?php endif; ?>
                                <?php if (in_array('bestseller', $tags)): ?>
                                    <span class="item-badge bestseller">
                                        <i class="fas fa-fire" style="margin-right:4px;"></i><?= $isEnglish ? 'POPULAR' : 'BÁN CHẠY' ?>
                                    </span>
                                <?php elseif (in_array('new', $tags)): ?>
                                    <span class="item-badge" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">
                                        <i class="fas fa-star" style="margin-right:4px;"></i><?= $isEnglish ? 'NEW' : 'MỚI' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="item-info">
                                <div class="item-main-row">
                                    <h3 class="item-name"><?= e($item['name']) ?></h3>
                                    <span class="item-price"><?= formatPrice($item['price']) ?></span>
                                </div>
                                <?php if ($isEnglish && !empty($item['name_en'])): ?>
                                    <div class="item-name-en"><?= e($item['name_en']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($itemDesc)): ?>
                                    <p class="item-desc"><?= e($itemDesc) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($tags)): ?>
                                <div class="item-tags">
                                    <?php foreach ($tags as $tag): 
                                        if (!in_array($tag, ['bestseller','new','spicy','vegetarian','recommended'])) continue;
                                        $tagLabels = ['spicy'=>['CAY','SPICY'],'vegetarian'=>['CHAY','VEGETARIAN'],'recommended'=>['ĐỀ XUẤT','RECOMMENDED']];
                                    ?>
                                        <span class="item-tag <?= $tag ?>"><?= $isEnglish ? $tagLabels[$tag][1] : $tagLabels[$tag][0] ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <div class="item-footer">
                                    <?php if (!$isUnavailable): ?>
                                    <button class="btn-add-circle" onclick="event.stopPropagation(); quickAdd(<?= $item['id'] ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
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
            <p><?= $isEnglish ? 'Menu not available' : 'Chưa có thực đơn' ?></p>
            <p><?= $isEnglish ? 'Please contact staff for assistance' : 'Vui lòng liên hệ nhân viên để được hỗ trợ' ?></p>
        </div>
        <?php endif; ?>

        <div id="searchNoResult" class="menu-empty-state" style="display:none;">
            <i class="fas fa-search"></i>
            <p><?= $isEnglish ? 'No dishes found' : 'Không tìm thấy món phù hợp' ?></p>
            <button onclick="clearMenuSearch()" class="btn-ghost" style="display:inline-flex;margin-top:12px;">
                <?= $isEnglish ? 'Clear search' : 'Xoá tìm kiếm' ?>
            </button>
        </div>
    </main>
</div>

<!-- Cart Bar -->
<div id="cartBar" class="cart-bar hidden">
    <div class="cart-bar-content">
        <div class="cart-icon-box">
            <i class="fas fa-shopping-basket"></i>
            <span class="cart-badge" id="cartCount">0</span>
        </div>
        <div class="cart-info">
            <span class="cart-label"><?= $isEnglish ? 'YOUR ORDER' : 'GIỎ HÀNG' ?></span>
            <span class="cart-total" id="cartTotal">0₫</span>
        </div>
        <button class="btn-view-cart" onclick="toggleCartModal()">
            <?= $isEnglish ? 'VIEW' : 'XEM' ?> <i class="fas fa-chevron-right" style="margin-left:6px;"></i>
        </button>
    </div>
</div>

<!-- Cart Modal -->
<div id="cartModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-cart me-2"></i> <?= $isEnglish ? 'Order Details' : 'Chi tiết đơn hàng' ?></h3>
            <button class="modal-close" onclick="toggleCartModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="cartItemsList" class="cart-items-container"></div>
            <div class="order-notes-box" style="margin-top:1.25rem;">
                <label><?= $isEnglish ? 'ORDER NOTES' : 'GHI CHÚ ĐƠN HÀNG' ?></label>
                <textarea id="orderNotes" placeholder="<?= $isEnglish ? 'e.g., No onion, less spicy...' : 'VD: Không lấy hành, ít cay...' ?>"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="total-summary">
                <span><?= $isEnglish ? 'TOTAL' : 'TỔNG CỘNG' ?></span>
                <strong id="modalCartTotal">0₫</strong>
            </div>
            <button class="btn-submit-order" id="btnSubmitOrder" onclick="submitOrder()">
                <i class="fas fa-paper-plane"></i> <span style="margin-left:8px;"><?= $isEnglish ? 'CONFIRM ORDER' : 'XÁC NHẬN ĐẶT MÓN' ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Item Detail Modal -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header" style="border:none;position:relative;padding-bottom:0;">
            <button class="modal-close-circle" onclick="closeItemDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="item-detail-img" id="detailImg"></div>
        <div class="modal-body">
            <div style="margin-bottom:1.25rem;">
                <h2 id="detailName" class="playfair" style="margin:0 0 6px;font-size:1.5rem;font-weight:800;color:#0f172a;"></h2>
                <div id="detailNameEn" class="item-name-en" style="font-size:0.9rem;color:#64748b;margin-bottom:8px;"></div>
                <div id="detailPrice" class="item-price" style="font-size:1.3rem;font-weight:800;color:var(--gold-dark);"></div>
                <p id="detailDesc" class="item-desc" style="margin-top:10px;font-size:0.9rem;color:#64748b;line-height:1.6;"></p>
            </div>
            <div class="order-controls">
                <div class="qty-control-premium">
                    <button onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty">1</span>
                    <button onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="note-input-box">
                    <i class="fas fa-edit"></i>
                    <input type="text" id="detailNote" placeholder="<?= $isEnglish ? 'Additional notes...' : 'Ghi chú thêm...' ?>">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-submit-order w-100" id="btnAddOrder" onclick="addFromDetail()">
                <i class="fas fa-cart-plus"></i> <span style="margin-left:8px;"><?= $isEnglish ? 'ADD TO ORDER' : 'THÊM VÀO ĐƠN' ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Bill Modal -->
<div id="billTamModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar me-2"></i> <?= $isEnglish ? 'Preliminary Bill' : 'Hoá đơn tạm tính' ?></h3>
            <button class="modal-close" onclick="closeBillTam()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="bill-items-container">
                <?php if ($hasItems): ?>
                    <?php foreach ($orderItems as $oi): if ($oi['status'] === 'cancelled') continue; ?>
                        <div class="bill-item">
                            <div class="bill-item-main">
                                <span class="bill-qty"><?= $oi['quantity'] ?>x</span>
                                <span class="bill-name"><?= e($currentLang === 'en' && !empty($oi['item_name_en']) ? $oi['item_name_en'] : $oi['item_name']) ?></span>
                                <span class="bill-price"><?= formatPrice($oi['item_price'] * $oi['quantity']) ?></span>
                            </div>
                            <?php if (!empty($oi['note'])): ?>
                                <div style="font-size:0.725rem;color:#94a3b8;padding-left:44px;margin-top:4px;">
                                    <i class="fas fa-pen" style="font-size:0.6rem;margin-right:4px;"></i> <?= e($oi['note']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="bill-item-status <?= $oi['status'] ?>">
                                <?php $st = ['confirmed'=>'✅ '.$isEnglish?'Confirmed':'Đã xác nhận','pending'=>'⏳ Pending','draft'=>'📝 Draft']; echo $st[$oi['status']] ?? $oi['status']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="bill-summary">
                        <div class="bill-total-row">
                            <span><?= $isEnglish ? 'Subtotal' : 'Tổng tiền món' ?></span>
                            <strong><?= formatPrice($orderTotal) ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="menu-empty-state" style="padding:2rem 1rem;">
                        <i class="fas fa-receipt"></i>
                        <p><?= $isEnglish ? 'No items ordered yet.' : 'Bàn chưa có món nào được gọi.' ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;flex-direction:column;gap:12px;">
            <button class="btn-gold w-100" onclick="callWaiter('payment')">
                <i class="fas fa-hand-holding-usd me-2"></i> <?= $isEnglish ? 'REQUEST PAYMENT' : 'YÊU CẦU THANH TOÁN' ?>
            </button>
            <button class="btn-ghost w-100" onclick="closeBillTam()">
                <?= $isEnglish ? 'CONTINUE ORDERING' : 'TIẾP TỤC ĐẶT MÓN' ?>
            </button>
        </div>
    </div>
</div>

<!-- Config -->
<script>
const CUSTOMER_CONFIG = {
    tableId: <?= $table['id'] ?>,
    tableName: '<?= e($table['name']) ?>',
    isRoomService: <?= $isRoomService ? 'true' : 'false' ?>,
    baseUrl: '<?= BASE_URL ?>',
    hasItems: <?= $hasItems ? 'true' : 'false' ?>,
    restaurantCoords: { lat: <?= RESTAURANT_LAT ?>, lng: <?= RESTAURANT_LNG ?> },
    maxDistance: <?= MAX_ORDER_DISTANCE ?>,
    devMode: <?= !empty($devMode) ? 'true' : 'false' ?>
};
let currentLang = '<?= $currentLang ?>';
</script>

<!-- Customer Menu JS -->
<script>
// Type tab filter
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
    });
});

// Search
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

// Language Toggle
function toggleLanguage() {
    currentLang = currentLang === 'vi' ? 'en' : 'vi';
    localStorage.setItem('aurora_lang', currentLang);
    document.cookie = "aurora_lang=" + currentLang + "; path=/; max-age=31536000; SameSite=Lax";
    location.reload();
}

// Item Detail
let currentItem = null;
let detailQty = 1;

function showItemDetail(item) {
    currentItem = item;
    detailQty = 1;
    
    const isEn = currentLang === 'en';
    document.getElementById('detailName').textContent = isEn && item.name_en ? item.name_en : item.name;
    document.getElementById('detailNameEn').textContent = isEn && item.name_en ? item.name : (item.name_en || '');
    document.getElementById('detailPrice').textContent = formatPrice(item.price);
    document.getElementById('detailDesc').textContent = isEn && item.description_en ? item.description_en : (item.description || '');
    document.getElementById('detailQty').textContent = '1';
    document.getElementById('detailNote').value = '';
    
    if (item.image) {
        document.getElementById('detailImg').style.backgroundImage = `url(${BASE_URL}/public/uploads/${item.image})`;
    } else {
        document.getElementById('detailImg').style.backgroundImage = 'none';
        document.getElementById('detailImg').innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f5f5f5,#e8e8e8);color:#d0d0d0;font-size:3rem;"><i class="fas fa-utensils"></i></div>';
    }
    
    document.getElementById('itemDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeItemDetail() {
    document.getElementById('itemDetailModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function changeDetailQty(delta) {
    detailQty = Math.max(1, detailQty + delta);
    document.getElementById('detailQty').textContent = detailQty;
}

function addFromDetail() {
    if (!currentItem) return;
    quickAdd(currentItem.id, detailQty);
    closeItemDetail();
}

// Quick Add (placeholder - needs full implementation)
function quickAdd(itemId, qty = 1) {
    alert('Tính năng đang được cập nhật. Vui lòng liên hệ nhân viên để đặt món.');
}

// Cart Modal
function toggleCartModal() {
    const modal = document.getElementById('cartModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        renderCartItems();
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function renderCartItems() {
    // Placeholder - needs full implementation
    document.getElementById('cartItemsList').innerHTML = '<div class="menu-empty-state"><p>Giỏ hàng trống</p></div>';
    document.getElementById('modalCartTotal').textContent = '0₫';
}

// Bill Modal
function showBillTam() {
    document.getElementById('billTamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBillTam() {
    document.getElementById('billTamModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Call Waiter
function callWaiter(type) {
    alert('Tính năng đang được cập nhật. Vui lòng liên hệ nhân viên trực tiếp.');
}

// Submit Order
function submitOrder() {
    alert('Tính năng đang được cập nhật. Vui lòng liên hệ nhân viên để đặt món.');
}

// Location handling
document.getElementById('btnAllowLocation')?.addEventListener('click', () => {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const { latitude, longitude } = pos.coords;
                const dist = getDistanceFromLatLonInKm(latitude, longitude, CUSTOMER_CONFIG.restaurantCoords.lat, CUSTOMER_CONFIG.restaurantCoords.lng) * 1000;
                if (dist <= CUSTOMER_CONFIG.maxDistance || CUSTOMER_CONFIG.devMode) {
                    localStorage.setItem('locationVerified_table_' + CUSTOMER_CONFIG.tableId, 'true');
                    document.getElementById('locationOverlay').style.display = 'none';
                    document.getElementById('menuWrapper').style.display = 'block';
                } else {
                    showLocationError('Bạn ở quá xa nhà hàng (' + Math.round(dist) + 'm)');
                }
            },
            (err) => showLocationError(err.message)
        );
    } else {
        showLocationError('Trình duyệt không hỗ trợ định vị');
    }
});

function showLocationError(msg) {
    const el = document.getElementById('locationError');
    el.textContent = '⚠ ' + msg;
    el.style.display = 'block';
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function deg2rad(deg) { return deg * (Math.PI/180); }

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + '₫';
}
</script>

</body>
</html>