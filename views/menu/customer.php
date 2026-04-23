<!-- Language toggle & FAB styles -->
<style>
/* Language toggle premium */
.lang-toggle-btn {
    background: rgba(255,255,255,0.9);
    border: 1.5px solid rgba(212,175,55,0.3);
    border-radius: 10px;
    padding: 8px 14px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.75rem;
    color: var(--gold-dark, #a68341);
    transition: all 0.25s;
    display: flex;
    align-items: center;
    gap: 6px;
    backdrop-filter: blur(10px);
}
.lang-toggle-btn:hover {
    background: linear-gradient(135deg, var(--gold, #c5a059), var(--gold-dark, #a68341));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(197,160,89,0.3);
}

/* Floating Action Button Premium */
.fab-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    gap: 18px;
    align-items: flex-end;
}

.fab-main {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff;
    border: none;
    box-shadow: 0 8px 30px rgba(197,160,89,0.45);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    padding: 0;
}

.fab-main-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1px;
    margin-top: -2px;
    text-transform: uppercase;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.fab-main:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 40px rgba(197,160,89,0.55);
}

.fab-main.active {
    transform: rotate(45deg);
}

.fab-menu {
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
    opacity: 0;
}

.fab-menu.show {
    max-height: 400px;
    opacity: 1;
}

.fab-item-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.fab-item {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #fff;
    color: var(--gold-dark);
    border: 2px solid var(--gold);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.25s;
    position: relative;
}

.fab-item:hover {
    transform: scale(1.12);
    background: var(--gold);
    color: #fff;
    box-shadow: 0 8px 25px rgba(197,160,89,0.45);
}

.fab-item.has-items {
    animation: fabPulse 2s infinite;
}

@keyframes fabPulse {
    0%, 100% { box-shadow: 0 6px 20px rgba(197,160,89,0.4); }
    50% { box-shadow: 0 6px 30px rgba(197,160,89,0.65); }
}

.fab-status-dot {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 10px;
    height: 10px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid #fff;
    animation: dotPulse 1.5s infinite;
}

@keyframes dotPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.85; }
}

.fab-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    white-space: nowrap;
    text-shadow: 0 1px 2px rgba(255,255,255,0.9);
    background: rgba(255,255,255,0.95);
    padding: 4px 10px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.fab-tooltip {
    display: none;
}

/* Cart Bar Premium */
.cart-bar {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: auto;
    max-width: calc(100% - 120px);
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: white;
    border-radius: 18px;
    padding: 14px 18px;
    z-index: 1000;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    min-height: 64px;
    border: 1px solid rgba(255,255,255,0.1);
}

.cart-bar.hidden { transform: translateY(150%); }

.cart-bar-content {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.cart-icon-box {
    position: relative;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
    width: 50px;
    height: 50px;
    min-width: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    border: 1px solid rgba(255,255,255,0.1);
}

.cart-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: white;
    font-size: 0.7rem;
    font-weight: 800;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #1e293b;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.cart-info { flex: 1; min-width: 0; }
.cart-label { 
    display: block; 
    font-size: 0.675rem; 
    opacity: 0.75; 
    white-space: nowrap; 
    overflow: hidden; 
    text-overflow: ellipsis;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cart-total { 
    font-size: 1.05rem;
    font-weight: 800; 
    color: var(--gold); 
    white-space: nowrap;
    display: block;
    margin-top: 2px;
}

.btn-view-cart {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border: none;
    color: white;
    padding: 12px 18px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.8rem;
    white-space: nowrap;
    flex-shrink: 0;
    cursor: pointer;
    transition: all 0.25s;
    min-height: 44px;
    box-shadow: 0 4px 15px rgba(197,160,89,0.3);
}

.btn-view-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(197,160,89,0.4);
}

/* Modals Premium */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px) saturate(180%);
    z-index: 2000;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    transition: opacity 0.35s;
    padding: 0;
}

.modal-backdrop.hidden { opacity: 0; pointer-events: none; }

.modal {
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}

.modal-bottom {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    max-width: 100%;
    border-radius: 28px 28px 0 0;
    max-height: 88vh;
    animation: slideUpPremium 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideUpPremium { 
    from { transform: translateY(100%); } 
    to { transform: translateY(0); } 
}

.modal-header {
    padding: 24px 22px;
    border-bottom: 1px solid rgba(226,234,252,0.6);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,250,252,0.5));
}

.modal-header h3 { 
    margin: 0; 
    font-size: 1.25rem; 
    font-weight: 800; 
    color: #0f172a;
    letter-spacing: -0.3px;
}

.modal-close {
    background: rgba(241,245,249,0.8);
    border: none;
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    flex-shrink: 0;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: rotate(90deg);
}

.modal-body { 
    padding: 24px 22px; 
    overflow-y: auto; 
    flex: 1; 
    min-height: 0;
}

.cart-items-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal-footer {
    padding: 22px;
    border-top: 1px solid rgba(226,234,252,0.6);
    box-shadow: 0 -8px 30px rgba(0,0,0,0.04);
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,250,252,0.5));
}

.total-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding: 16px 18px;
    background: linear-gradient(135deg, rgba(248,250,252,0.6), rgba(255,255,255,0.4));
    border-radius: 14px;
    border: 1px solid rgba(226,234,252,0.4);
}

.total-summary span { 
    color: #475569; 
    font-weight: 700; 
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.total-summary strong { 
    font-size: 1.5rem;
    color: var(--gold-dark, #a68341); 
    font-weight: 800; 
}

.btn-submit-order {
    width: 100%;
    background: linear-gradient(135deg, var(--gold, #c5a059), var(--gold-dark, #a68341));
    color: white;
    border: none;
    padding: 18px;
    border-radius: 16px;
    font-weight: 800;
    font-size: 1rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    box-shadow: 0 10px 25px rgba(197,160,89,0.35);
    min-height: 58px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-submit-order:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(197,160,89,0.45);
}

.btn-submit-order:active {
    transform: translateY(0) scale(0.98);
}

.order-notes-box textarea {
    width: 100%;
    background: rgba(248,250,252,0.8);
    border: 1.5px solid rgba(226,234,252,0.6);
    border-radius: 14px;
    padding: 16px;
    height: 100px;
    resize: none;
    font-family: inherit;
    font-size: 0.9rem;
    color: #334155;
    transition: all 0.2s;
}

.order-notes-box textarea:focus {
    outline: none;
    border-color: var(--gold, #c5a059);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(197,160,89,0.1);
}

.order-notes-box label {
    font-size: 0.7rem;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    display: block;
    margin-bottom: 10px;
}

/* Item Detail Modal Premium */
.modal-premium {
    border-radius: 28px 28px 0 0;
}

.item-detail-img {
    width: 100%;
    height: 260px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.item-detail-img::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, rgba(248,250,252,1), transparent);
    pointer-events: none;
}

.modal-close-circle {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    color: #fff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    cursor: pointer;
    backdrop-filter: blur(10px);
    transition: all 0.2s;
}

.modal-close-circle:hover {
    background: rgba(0,0,0,0.7);
    transform: scale(1.05);
}

.qty-control-premium {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.qty-control-premium button {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, rgba(197,160,89,0.1), rgba(166,131,65,0.05));
    color: var(--gold-dark);
    font-size: 1.3rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    box-shadow: 0 2px 10px rgba(197,160,89,0.15);
}

.qty-control-premium button:hover {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff;
    box-shadow: 0 4px 20px rgba(197,160,89,0.3);
    transform: scale(1.05);
}

.qty-control-premium span {
    font-size: 1.5rem;
    font-weight: 800;
    min-width: 48px;
    text-align: center;
    color: #0f172a;
}

.note-input-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(248,250,252,0.8);
    border: 1.5px solid rgba(226,234,252,0.6);
    border-radius: 14px;
    padding: 14px 18px;
}

.note-input-box i {
    color: #94a3b8;
    font-size: 1.1rem;
}

.note-input-box input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.9rem;
    color: #334155;
}

.note-input-box input::placeholder {
    color: #94a3b8;
}

/* Opt chip premium */
.opt-chip-premium {
    padding: 8px 16px;
    background: rgba(248,250,252,0.8);
    color: #475569;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: 1.5px solid rgba(226,234,252,0.6);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.opt-chip-premium.active {
    background: rgba(197,160,89,0.12);
    color: var(--gold-dark, #a68341);
    border-color: var(--gold, #c5a059);
    transform: scale(1.03);
    box-shadow: 0 2px 10px rgba(197,160,89,0.15);
}

/* Responsive */
@media (max-width: 480px) {
    .fab-container {
        bottom: 18px;
        right: 18px;
    }
    
    .fab-main {
        width: 62px;
        height: 62px;
        font-size: 1.35rem;
    }
    
    .fab-main-label {
        font-size: 0.6rem;
    }
    
    .fab-item {
        width: 52px;
        height: 52px;
        font-size: 1.15rem;
    }
    
    .cart-bar {
        bottom: 16px;
        left: 16px;
        max-width: calc(100% - 100px);
        padding: 12px 14px;
        min-height: 58px;
    }
    
    .cart-icon-box {
        width: 46px;
        height: 46px;
        min-width: 46px;
    }
    
    .cart-total {
        font-size: 0.95rem;
    }
    
    .btn-view-cart {
        padding: 10px 14px;
        font-size: 0.75rem;
        min-height: 40px;
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
                         data-options="<?= e($item['note_options'] ?? '') ?>"
                         data-options-en="<?= e($item['note_options_en'] ?? '') ?>"
                         data-unavailable-text="<?= $isEnglish ? 'UNAVAILABLE' : 'HẾT HÀNG' ?>"
                         onclick="<?= $isUnavailable ? '' : 'showItemDetail(' . e(json_encode($item)) . ')' ?>">

                        <div class="item-img-box">
                            <?php if ($item['image']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>"
                                     alt="<?= e($itemName) ?>" loading="lazy">
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
                                    $tagLabels = [
                                        'spicy' => ['CAY', 'SPICY'],
                                        'vegetarian' => ['CHAY', 'VEGETARIAN'],
                                        'recommended' => ['ĐỀ XUẤT', 'RECOMMENDED']
                                    ];
                                ?>
                                    <span class="item-tag <?= $tag ?>">
                                        <?= $isEnglish ? $tagLabels[$tag][1] : $tagLabels[$tag][0] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="item-footer">
                                <?php if (!$isUnavailable): ?>
                                <button class="btn-add-circle"
                                        onclick="event.stopPropagation(); quickAdd(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>, '<?= e($item['name_en'] ?? '') ?>')">
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

<!-- Floating Action Button -->
<div class="fab-container">
    <div class="fab-menu" id="fabMenu">
        <div class="fab-item-wrapper">
            <button class="fab-item" onclick="callWaiter('support')" title="<?= $isEnglish ? 'Call staff' : 'Gọi nhân viên' ?>">
                <i class="fas fa-<?= $isRoomService ? 'concierge-bell' : 'hand-paper' ?>"></i>
            </button>
            <span class="fab-label"><?= $isEnglish ? ($isRoomService ? 'CALL RECEPTION' : 'CALL WAITER') : 'GỌI NHÂN VIÊN' ?></span>
        </div>
        <div class="fab-item-wrapper">
            <button class="fab-item <?= $hasItems ? 'has-items' : '' ?>" onclick="<?= $hasItems ? 'showBillTam()' : "callWaiter('payment')" ?>" title="<?= $hasItems ? ($isEnglish ? 'Bill' : 'Hoá đơn') : ($isEnglish ? 'Payment' : 'Thanh toán') ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <?php if ($hasItems): ?>
                    <span class="fab-status-dot"></span>
                <?php endif; ?>
            </button>
            <span class="fab-label"><?= $hasItems ? ($isEnglish ? 'BILL' : 'HOÁ ĐƠN') : ($isEnglish ? 'PAYMENT' : 'THANH TOÁN') ?></span>
        </div>
        <div class="fab-item-wrapper">
            <button class="fab-item" onclick="window.location.href='<?= BASE_URL ?>/qr/landing'" title="<?= $isEnglish ? 'History' : 'Lịch sử' ?>">
                <i class="fas fa-history"></i>
            </button>
            <span class="fab-label"><?= $isEnglish ? 'HISTORY' : 'LỊCH SỬ' ?></span>
        </div>
        <div class="fab-item-wrapper">
            <button class="fab-item" onclick="window.location.reload()" title="<?= $isEnglish ? 'Refresh' : 'Làm mới' ?>">
                <i class="fas fa-sync-alt"></i>
            </button>
            <span class="fab-label"><?= $isEnglish ? 'REFRESH' : 'LÀM MỚI' ?></span>
        </div>
    </div>
    <button class="fab-main" id="fabMain" onclick="toggleFab()">
        <i class="fas fa-bars" id="fabIcon"></i>
        <span class="fab-main-label">MENU</span>
    </button>
</div>

<!-- Cart Bar Premium -->
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
            <?= $isEnglish ? 'VIEW ORDER' : 'XEM GIỎ' ?> <i class="fas fa-chevron-right" style="margin-left:6px;"></i>
        </button>
    </div>
</div>

<!-- Cart Modal Premium -->
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

<!-- Item Detail Modal Premium -->
<div id="itemDetailModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header" style="border:none;position:relative;padding-bottom:0;">
            <button class="modal-close-circle" onclick="closeItemDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="item-detail-img" id="detailImg" style="width:100%;height:260px;background-size:cover;background-position:center;position:relative;"></div>
        <div class="modal-body">
            <div style="margin-bottom:1.25rem;">
                <h2 id="detailName" class="playfair" style="margin:0 0 6px;font-size:1.5rem;font-weight:800;color:#0f172a;"></h2>
                <div id="detailNameEn" class="item-name-en" style="font-size:0.9rem;color:#64748b;margin-bottom:8px;"></div>
                <div id="detailPrice" class="item-price" style="font-size:1.3rem;font-weight:800;color:var(--gold-dark);"></div>
                <p id="detailDesc" class="item-desc" style="margin-top:10px;font-size:0.9rem;color:#64748b;line-height:1.6;"></p>
            </div>
            <div id="detailOptsWrap" style="display:none;margin-bottom:1.25rem;">
                <label style="font-size:0.7rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1.2px;margin-bottom:12px;display:block;">
                    <?= $isEnglish ? 'QUICK OPTIONS' : 'TUỲ CHỌN NHANH' ?>
                </label>
                <div id="detailOptsContainer" style="display:flex;flex-wrap:wrap;gap:10px;"></div>
            </div>
            <div class="order-controls">
                <div class="qty-control-premium">
                    <button onclick="changeDetailQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="detailQty">1</span>
                    <button onclick="changeDetailQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="note-input-box">
                    <i class="fas fa-edit"></i>
                    <input type="text" id="detailNote" placeholder="<?= $isEnglish ? 'Additional notes (No onion, less spicy...)' : 'Ghi chú thêm (Không hành, ít cay...)' ?>">
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

<!-- Bill Modal Premium -->
<div id="billTamModal" class="modal-backdrop hidden">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar me-2"></i> <?= $isEnglish ? 'Preliminary Bill' : 'Hoá đơn tạm tính' ?></h3>
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
                                <span class="bill-name"><?= e($currentLang === 'en' && !empty($oi['item_name_en']) ? $oi['item_name_en'] : $oi['item_name']) ?></span>
                                <span class="bill-price"><?= formatPrice($oi['item_price'] * $oi['quantity']) ?></span>
                            </div>
                            <?php if (!empty($oi['note'])): ?>
                                <div style="font-size:0.725rem;color:#94a3b8;padding-left:44px;margin-top:4px;">
                                    <i class="fas fa-pen" style="font-size:0.6rem;margin-right:4px;"></i> <?= e($oi['note']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="bill-item-status <?= $oi['status'] ?>">
                                <?php
                                $statusTxt = ['confirmed'=>'✅ '.$isEnglish?'Confirmed':'Đã xác nhận','pending'=>'⏳ '.$isEnglish?'Pending':'Chờ xác nhận','draft'=>'📝 '.$isEnglish?'Draft':'Chờ xác nhận'];
                                echo $statusTxt[$oi['status']] ?? $oi['status'];
                                ?>
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

// Language state
let currentLang = '<?= $currentLang ?>';

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

/* ── FAB Toggle ── */
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
    toggleFab();
    document.getElementById('billTamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeBillTam() {
    document.getElementById('billTamModal').classList.add('hidden');
    document.body.style.overflow = '';
}

/* ── Language Toggle VI/EN ── */
function toggleLanguage() {
    currentLang = currentLang === 'vi' ? 'en' : 'vi';
    localStorage.setItem('aurora_lang', currentLang);
    document.cookie = "aurora_lang=" + currentLang + "; path=/; max-age=31536000; SameSite=Lax";
    location.reload();
}
</script>