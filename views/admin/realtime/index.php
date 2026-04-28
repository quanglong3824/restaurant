<?php
// views/admin/realtime/index.php — Professional POS-Style Monitoring with Full Inline Actions
?>

<div class="pos-monitor light-theme">
    <!-- Top Command Bar -->
    <div class="command-bar">
        <div class="brand-unit">
            <span class="unit-code">SYSTEM MONITOR</span>
            <h1 class="unit-name">ĐIỀU HÀNH TRỰC TIẾP</h1>
        </div>
        
        <div class="system-stats">
            <div class="stat-item">
                <span class="label">ĐANG PHỤC VỤ</span>
                <span class="value highlight" id="statOccupied"><?= $counts['occupied'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">BÀN TRỐNG</span>
                <span class="value" id="statAvailable"><?= $counts['available'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">DOANH THU TẠM TÍNH</span>
                <span class="value gold" id="statTempRevenue">...</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">THÔNG BÁO</span>
                <span class="value" id="statNotif">0</span>
            </div>
        </div>

        <div class="command-actions">
            <button onclick="openModalOpenTable()" class="cmd-btn primary">
                <i class="fas fa-door-open"></i>
                <span>MỞ BÀN</span>
            </button>
            <div class="sync-box">
                <div class="sync-timer">
                    <span id="reloadCount">8</span>s
                </div>
                <button onclick="refreshData()" class="refresh-circle-btn">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Monitoring Grid -->
    <div id="realtimeListContainer" class="pos-grid">
        <div class="pos-loader">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <span>Đang cập nhật dữ liệu từ các bàn...</span>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- MODAL: OPEN NEW TABLE -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<div class="pos-modal-backdrop" id="modalOpenTable">
    <div class="pos-modal">
        <div class="pos-modal-header">
            <h3><i class="fas fa-door-open"></i> Mở bàn mới</h3>
            <button class="pos-modal-close" onclick="closeModal('modalOpenTable')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-field">
                <label class="pos-label">Chọn bàn</label>
                <select id="openTableId" class="pos-select">
                    <option value="">-- Bàn trống --</option>
                    <?php 
                    $groupedTables = [];
                    foreach ($availableTables as $t) {
                        $area = $t['area'] ?? 'Chung';
                        $groupedTables[$area][] = $t;
                    }
                    foreach ($groupedTables as $area => $tables): 
                    ?>
                        <optgroup label="<?= e($area) ?>">
                            <?php foreach ($tables as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= $t['capacity'] ?> chỗ)</option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pos-field">
                <label class="pos-label">Số khách</label>
                <div class="pos-guest-grid">
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <button type="button" class="pos-guest-btn <?= $i === 2 ? 'active' : '' ?>" data-guest="<?= $i ?>" onclick="selectGuest(<?= $i ?>)">
                            <?= $i ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn-secondary" onclick="closeModal('modalOpenTable')">Hủy</button>
            <button class="pos-btn-primary" onclick="submitOpenTable()">
                <i class="fas fa-check"></i> MỞ BÀN
            </button>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- MODAL: ADD ITEMS -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<div class="pos-modal-backdrop" id="modalAddItems">
    <div class="pos-modal pos-modal-lg">
        <div class="pos-modal-header">
            <h3><i class="fas fa-plus"></i> Thêm món - <span id="addItemsTableName"></span></h3>
            <button class="pos-modal-close" onclick="closeModal('modalAddItems')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="menuSearchInput" placeholder="Tìm món..." onkeyup="filterMenuItems()">
            </div>
            <div class="pos-menu-grid" id="posMenuGrid">
                <?php foreach ($menuItems as $item): ?>
                    <div class="pos-menu-item" data-name="<?= e(strtolower($item['name'])) ?>" onclick="addItemToCart(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?= $item['price'] ?>)">
                        <div class="pos-menu-item-name"><?= e($item['name']) ?></div>
                        <div class="pos-menu-item-price"><?= formatPrice($item['price']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pos-cart-section">
                <div class="pos-cart-header">
                    <span>Món đã chọn</span>
                    <span id="cartItemCount">0 món</span>
                </div>
                <div class="pos-cart-items" id="posCartItems">
                    <div class="pos-cart-empty">Chưa có món nào</div>
                </div>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn-secondary" onclick="closeModal('modalAddItems')">Hủy</button>
            <button class="pos-btn-primary" onclick="submitAddItems()">
                <i class="fas fa-check"></i> THÊM MÓN
            </button>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- MODAL: ORDER DETAILS (View/Edit/Delete) -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<div class="pos-modal-backdrop" id="modalOrderDetails">
    <div class="pos-modal pos-modal-lg">
        <div class="pos-modal-header">
            <h3><i class="fas fa-receipt"></i> Chi tiết - <span id="detailsTableName"></span></h3>
            <button class="pos-modal-close" onclick="closeModal('modalOrderDetails')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-detail-info" id="posDetailInfo"></div>
            <div class="pos-detail-items" id="posDetailItems"></div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn-secondary" onclick="closeModal('modalOrderDetails')">Đóng</button>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- MODAL: UPDATE GUEST COUNT -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<div class="pos-modal-backdrop" id="modalUpdateGuest">
    <div class="pos-modal">
        <div class="pos-modal-header">
            <h3><i class="fas fa-user-friends"></i> Sửa số khách</h3>
            <button class="pos-modal-close" onclick="closeModal('modalUpdateGuest')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-field">
                <label class="pos-label">Số khách hiện tại: <span id="currentGuestDisplay"></span></label>
                <div class="pos-guest-grid">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <button type="button" class="pos-guest-btn" data-guest="<?= $i ?>" onclick="selectUpdateGuest(<?= $i ?>)">
                            <?= $i ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn-secondary" onclick="closeModal('modalUpdateGuest')">Hủy</button>
            <button class="pos-btn-primary" onclick="submitUpdateGuest()">
                <i class="fas fa-check"></i> CẬP NHẬT
            </button>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- MODAL: PAYMENT -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<div class="pos-modal-backdrop" id="modalPayment">
    <div class="pos-modal">
        <div class="pos-modal-header">
            <h3><i class="fas fa-credit-card"></i> Thanh toán - <span id="paymentTableName"></span></h3>
            <button class="pos-modal-close" onclick="closeModal('modalPayment')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-payment-total">
                <span class="pos-payment-label">TỔNG CỘNG</span>
                <span class="pos-payment-amount" id="paymentAmount">0đ</span>
            </div>
            <div class="pos-field">
                <label class="pos-label">Phương thức thanh toán</label>
                <div class="pos-method-grid">
                    <button type="button" class="pos-method-btn active" data-method="cash" onclick="selectPaymentMethod('cash')">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>TIỀN MẶT</span>
                    </button>
                    <button type="button" class="pos-method-btn" data-method="transfer" onclick="selectPaymentMethod('transfer')">
                        <i class="fas fa-university"></i>
                        <span>CHUYỂN KHOẢN</span>
                    </button>
                </div>
            </div>
            <div class="pos-checkbox-row" onclick="togglePaymentCheckbox()">
                <div class="pos-checkbox-box" id="paymentConfirmedBox">
                    <i class="fas fa-check"></i>
                </div>
                <span>Đã nhận đủ tiền</span>
                <input type="checkbox" id="paymentConfirmed" style="display:none">
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn-secondary" onclick="closeModal('modalPayment')">Hủy</button>
            <button class="pos-btn-primary" onclick="submitPayment()">
                <i class="fas fa-check-circle"></i> HOÀN TẤT
            </button>
        </div>
    </div>
</div>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- STYLES -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<style>
    :root {
        --pos-bg: #f8fafc;
        --pos-card: #ffffff;
        --pos-border: #e2e8f0;
        --pos-accent: #b8860b;
        --pos-text: #1e293b;
        --pos-text-muted: #64748b;
        --pos-success: #10b981;
        --pos-warning: #f59e0b;
        --pos-danger: #ef4444;
    }

    body { background-color: var(--pos-bg); color: var(--pos-text); font-family: 'Inter', -apple-system, sans-serif; }

    .command-bar {
        display: flex; justify-content: space-between; align-items: center;
        background: #ffffff; padding: 15px 30px; border-bottom: 2px solid var(--pos-border);
        position: sticky; top: 0; z-index: 100;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .brand-unit { display: flex; flex-direction: column; }
    .unit-code { font-size: 0.65rem; font-weight: 800; color: var(--pos-text-muted); letter-spacing: 2px; }
    .unit-name { font-size: 1.1rem; font-weight: 800; margin: 0; color: var(--pos-text); }

    .system-stats { display: flex; align-items: center; gap: 30px; }
    .stat-item { display: flex; flex-direction: column; align-items: center; }
    .stat-item .label { font-size: 0.6rem; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 2px; }
    .stat-item .value { font-size: 1.4rem; font-weight: 900; color: var(--pos-text); }
    .stat-item .value.highlight { color: var(--pos-warning); }
    .stat-item .value.gold { color: var(--pos-accent); }
    .stat-divider { width: 1px; height: 35px; background: var(--pos-border); }

    .sync-box { display: flex; align-items: center; gap: 12px; }
    .command-actions { display: flex; align-items: center; gap: 16px; }
    .cmd-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px; font-size: 0.75rem; font-weight: 800;
        cursor: pointer; transition: all 0.2s; border: none; text-decoration: none;
    }
    .cmd-btn.primary { background: var(--pos-accent); color: #ffffff; }
    .cmd-btn.primary:hover { box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3); transform: translateY(-1px); }
    
    .sync-timer { 
        width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--pos-border);
        display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--pos-text-muted);
    }
    .refresh-circle-btn {
        background: #f1f5f9; border: 1px solid var(--pos-border); color: var(--pos-text);
        width: 35px; height: 35px; border-radius: 50%; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center;
    }
    .refresh-circle-btn:hover { background: var(--pos-text); color: #fff; border-color: var(--pos-text); }

    .pos-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 25px; padding: 30px;
    }
    .pos-loader { grid-column: 1/-1; text-align: center; padding: 100px; color: var(--pos-text-muted); display: flex; flex-direction: column; gap: 15px; align-items: center; }

    .pos-card {
        background: var(--pos-card); border: 1px solid var(--pos-border);
        border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .pos-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    
    .card-header-pos {
        padding: 20px; background: #fdfdfd;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--pos-border);
    }
    .table-main-info h2 { font-size: 1.4rem; font-weight: 900; margin: 0; color: var(--pos-text); letter-spacing: -0.5px; }
    .table-sub-info { font-size: 0.75rem; color: var(--pos-text-muted); margin-top: 4px; display: flex; gap: 12px; font-weight: 500; }
    .table-sub-info .guest-edit { cursor: pointer; color: var(--pos-accent); }
    .table-sub-info .guest-edit:hover { text-decoration: underline; }
    
    .status-tag { padding: 6px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-tag.open { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-tag.closed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .status-tag.idle { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

    .card-body-pos { padding: 0; flex: 1; overflow-y: auto; max-height: 280px; }
    .pos-table { width: 100%; border-collapse: collapse; }
    .pos-table th { 
        position: sticky; top: 0; background: #f8fafc; 
        padding: 10px 20px; font-size: 0.65rem; color: var(--pos-text-muted);
        text-align: left; text-transform: uppercase; border-bottom: 1px solid var(--pos-border);
        letter-spacing: 0.5px;
    }
    .pos-table td { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
    
    .item-title { font-weight: 700; color: var(--pos-text); }
    .item-note { font-size: 0.75rem; color: var(--pos-danger); margin-top: 3px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
    .qty-badge { font-weight: 800; color: var(--pos-text); text-align: center; }
    .price-col { text-align: right; color: var(--pos-text); font-weight: 600; font-family: 'JetBrains Mono', 'Monaco', monospace; }

    .card-footer-pos {
        padding: 20px; background: #fcfcfc;
        border-top: 1px solid var(--pos-border);
    }
    .total-summary { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .summary-left { display: flex; flex-direction: column; }
    .summary-left span:first-child { font-size: 0.6rem; color: var(--pos-text-muted); font-weight: 800; text-transform: uppercase; }
    .summary-left span:last-child { font-size: 1rem; font-weight: 800; color: var(--pos-text); }
    
    .summary-right { text-align: right; }
    .total-label { font-size: 0.65rem; color: var(--pos-text-muted); font-weight: 800; display: block; margin-bottom: 2px; }
    .total-value { font-size: 1.4rem; font-weight: 900; color: var(--pos-accent); }

    .action-row { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-pos {
        padding: 10px 16px; border-radius: 10px; font-size: 0.75rem; font-weight: 800;
        cursor: pointer; transition: all 0.2s; border: none;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        text-decoration: none;
    }
    .btn-pos-primary { background: var(--pos-text); color: #ffffff; }
    .btn-pos-primary:hover { background: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .btn-pos-gold { background: var(--pos-accent); color: #ffffff; }
    .btn-pos-gold:hover { box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3); }
    .btn-pos-outline { background: transparent; color: var(--pos-text); border: 2px solid var(--pos-border); }
    .btn-pos-outline:hover { background: var(--pos-bg); border-color: var(--pos-text); }
    .btn-pos-danger { background: var(--pos-danger); color: #ffffff; }
    .btn-pos-danger:hover { box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
    
    /* POS Modal */
    .pos-modal-backdrop {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center;
        z-index: 10000; padding: 20px;
    }
    .pos-modal-backdrop.is-open { display: flex; }
    
    .pos-modal {
        background: var(--pos-card); border-radius: 16px;
        width: 100%; max-width: 400px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        animation: modalSlideIn 0.3s ease-out;
    }
    .pos-modal-lg { max-width: 600px; max-height: 80vh; display: flex; flex-direction: column; }
    
    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pos-modal-header {
        padding: 20px; border-bottom: 1px solid var(--pos-border);
        display: flex; justify-content: space-between; align-items: center;
    }
    .pos-modal-header h3 { font-size: 1.1rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; }
    .pos-modal-header h3 i { color: var(--pos-accent); }
    .pos-modal-close {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--pos-bg); border: none; color: var(--pos-text-muted);
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: all 0.2s;
    }
    .pos-modal-close:hover { background: var(--pos-border); color: var(--pos-text); }
    
    .pos-modal-body { padding: 20px; overflow-y: auto; flex: 1; }
    .pos-modal-footer {
        padding: 20px; border-top: 1px solid var(--pos-border);
        display: flex; gap: 12px; justify-content: flex-end;
    }
    
    .pos-field { margin-bottom: 20px; }
    .pos-label { font-size: 0.75rem; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 10px; display: block; }
    .pos-select {
        width: 100%; padding: 12px 16px; border: 2px solid var(--pos-border);
        border-radius: 8px; font-size: 0.9rem; background: var(--pos-bg);
    }
    .pos-select:focus { border-color: var(--pos-accent); outline: none; }
    
    .pos-guest-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 8px; }
    .pos-guest-btn {
        padding: 12px; background: var(--pos-bg); border: 2px solid var(--pos-border);
        border-radius: 8px; font-size: 0.9rem; font-weight: 800; cursor: pointer;
        transition: all 0.2s;
    }
    .pos-guest-btn:hover { border-color: var(--pos-accent); }
    .pos-guest-btn.active { background: var(--pos-accent); color: white; border-color: var(--pos-accent); }
    
    .pos-btn-primary {
        padding: 12px 24px; background: var(--pos-accent); color: white;
        border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 800; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
    }
    .pos-btn-primary:hover { box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3); }
    
    .pos-btn-secondary {
        padding: 12px 24px; background: var(--pos-bg); color: var(--pos-text);
        border: 2px solid var(--pos-border); border-radius: 8px; font-size: 0.85rem; font-weight: 800; cursor: pointer;
    }
    .pos-btn-secondary:hover { background: var(--pos-border); }
    
    /* Add Items Modal */
    .pos-search-box {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; background: var(--pos-bg); border: 2px solid var(--pos-border);
        border-radius: 8px; margin-bottom: 16px;
    }
    .pos-search-box i { color: var(--pos-text-muted); }
    .pos-search-box input {
        flex: 1; border: none; background: transparent; font-size: 0.9rem;
    }
    .pos-search-box input:focus { outline: none; }
    
    .pos-menu-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px; max-height: 180px; overflow-y: auto; margin-bottom: 16px;
    }
    .pos-menu-item {
        padding: 10px; background: var(--pos-bg); border: 2px solid var(--pos-border);
        border-radius: 8px; cursor: pointer; transition: all 0.2s;
    }
    .pos-menu-item:hover { border-color: var(--pos-accent); background: rgba(184, 134, 11, 0.05); }
    .pos-menu-item-name { font-size: 0.8rem; font-weight: 700; color: var(--pos-text); }
    .pos-menu-item-price { font-size: 0.7rem; color: var(--pos-accent); font-weight: 800; margin-top: 4px; }
    
    .pos-cart-section { background: var(--pos-bg); border-radius: 8px; padding: 12px; }
    .pos-cart-header {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.8rem; font-weight: 800; color: var(--pos-text-muted); margin-bottom: 12px;
    }
    .pos-cart-items { max-height: 120px; overflow-y: auto; }
    .pos-cart-empty { text-align: center; color: var(--pos-text-muted); font-size: 0.85rem; padding: 20px; }
    
    .pos-cart-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 0; border-bottom: 1px solid var(--pos-border);
    }
    .pos-cart-item:last-child { border-bottom: none; }
    .pos-cart-item-name { font-size: 0.85rem; font-weight: 700; flex: 1; }
    .pos-cart-item-qty { display: flex; align-items: center; gap: 8px; }
    .pos-cart-qty-btn {
        width: 28px; height: 28px; border-radius: 4px;
        background: var(--pos-card); border: 1px solid var(--pos-border);
        display: flex; align-items: center; justify-content: center; cursor: pointer;
    }
    .pos-cart-qty-btn:hover { background: var(--pos-accent); color: white; border-color: var(--pos-accent); }
    .pos-cart-qty-num { font-size: 0.9rem; font-weight: 800; min-width: 30px; text-align: center; }
    
    /* Payment Modal */
    .pos-payment-total {
        text-align: center; padding: 20px; background: var(--pos-bg);
        border-radius: 8px; margin-bottom: 20px;
    }
    .pos-payment-label { font-size: 0.65rem; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; }
    .pos-payment-amount { font-size: 1.8rem; font-weight: 900; color: var(--pos-accent); margin-top: 8px; }
    
    .pos-method-grid { display: flex; gap: 12px; }
    .pos-method-btn {
        flex: 1; padding: 16px; background: var(--pos-bg); border: 2px solid var(--pos-border);
        border-radius: 8px; display: flex; flex-direction: column; align-items: center; gap: 8px;
        cursor: pointer; transition: all 0.2s;
    }
    .pos-method-btn i { font-size: 1.2rem; color: var(--pos-text-muted); }
    .pos-method-btn span { font-size: 0.75rem; font-weight: 800; color: var(--pos-text); }
    .pos-method-btn:hover { border-color: var(--pos-accent); }
    .pos-method-btn.active { background: rgba(184, 134, 11, 0.1); border-color: var(--pos-accent); }
    .pos-method-btn.active i, .pos-method-btn.active span { color: var(--pos-accent); }
    
    .pos-checkbox-row {
        display: flex; align-items: center; gap: 12px;
        padding: 12px; background: var(--pos-bg); border-radius: 8px; cursor: pointer;
        margin-top: 16px;
    }
    .pos-checkbox-box {
        width: 24px; height: 24px; border-radius: 4px;
        background: var(--pos-card); border: 2px solid var(--pos-border);
        display: flex; align-items: center; justify-content: center;
        color: var(--pos-text-muted); transition: all 0.2s;
    }
    .pos-checkbox-row.checked .pos-checkbox-box { background: var(--pos-success); border-color: var(--pos-success); color: white; }
    
    /* Detail Modal */
    .pos-detail-info {
        padding: 16px; background: var(--pos-bg); border-radius: 8px; margin-bottom: 16px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .pos-detail-info span { font-size: 0.85rem; color: var(--pos-text); }
    .pos-detail-info strong { font-weight: 800; }
    
    .pos-detail-items { max-height: 300px; overflow-y: auto; }
    .pos-detail-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px; background: var(--pos-bg); border-radius: 8px; margin-bottom: 8px;
    }
    .pos-detail-item-info { flex: 1; }
    .pos-detail-item-name { font-size: 0.9rem; font-weight: 700; color: var(--pos-text); }
    .pos-detail-item-note { font-size: 0.75rem; color: var(--pos-danger); margin-top: 4px; }
    .pos-detail-item-qty { font-size: 0.85rem; font-weight: 800; color: var(--pos-text); margin-right: 16px; }
    .pos-detail-item-price { font-size: 0.85rem; font-weight: 700; color: var(--pos-accent); }
    .pos-detail-item-actions { display: flex; gap: 8px; }
    .pos-detail-item-btn {
        width: 32px; height: 32px; border-radius: 6px; background: var(--pos-card);
        border: 1px solid var(--pos-border); display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .pos-detail-item-btn:hover { background: var(--pos-accent); color: white; border-color: var(--pos-accent); }
    .pos-detail-item-btn.delete:hover { background: var(--pos-danger); border-color: var(--pos-danger); }
    
    /* Responsive */
    @media (max-width: 768px) {
        .system-stats { display: none; }
        .pos-grid { grid-template-columns: 1fr; padding: 15px; }
        .command-bar { padding: 10px 20px; flex-wrap: wrap; gap: 12px; }
        .command-actions { order: 3; width: 100%; justify-content: center; }
        .action-row { flex-wrap: wrap; }
        .pos-modal { max-width: 100%; margin: 10px; }
        .pos-menu-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        .pos-guest-grid { grid-template-columns: repeat(4, 1fr); }
    }

    .card-body-pos::-webkit-scrollbar { width: 5px; }
    .card-body-pos::-webkit-scrollbar-track { background: transparent; }
    .card-body-pos::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<!-- ─────────────────────────────────────────────────────────────────── -->
<!-- JAVASCRIPT -->
<!-- ─────────────────────────────────────────────────────────────────── -->
<script>
const BASE_URL = '<?= BASE_URL ?>';
let timerCount = 8;
let isRefreshing = false;

let currentOrderId = null;
let currentTableId = null;
let currentGuestCount = 2;
let currentPaymentMethod = 'cash';
let addItemsCart = [];
let orderItemsCache = {};

// ── Modal Helper ─────────────────────────────────────────────────────
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('is-open');
}

// ── Open Table ───────────────────────────────────────────────────────
function openModalOpenTable() {
    document.getElementById('modalOpenTable').classList.add('is-open');
}

function selectGuest(n) {
    currentGuestCount = n;
    document.querySelectorAll('#modalOpenTable .pos-guest-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.guest == n);
    });
}

function submitOpenTable() {
    const tableId = document.getElementById('openTableId').value;
    if (!tableId) {
        alert('Vui lòng chọn bàn!');
        return;
    }
    
    fetch(BASE_URL + '/tables/open', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: tableId, guest_count: currentGuestCount })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeModal('modalOpenTable');
            refreshData();
        } else {
            alert(data.message || 'Lỗi mở bàn');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi kết nối');
    });
}

// ── Add Items ────────────────────────────────────────────────────────
function openModalAddItems(orderId, tableId, tableName) {
    currentOrderId = orderId;
    currentTableId = tableId;
    addItemsCart = [];
    document.getElementById('addItemsTableName').textContent = tableName;
    document.getElementById('modalAddItems').classList.add('is-open');
    document.getElementById('menuSearchInput').value = '';
    filterMenuItems();
    updateCartUI();
}

function filterMenuItems() {
    const search = document.getElementById('menuSearchInput').value.toLowerCase();
    document.querySelectorAll('.pos-menu-item').forEach(item => {
        const name = item.dataset.name;
        item.style.display = name.includes(search) ? '' : 'none';
    });
}

function addItemToCart(id, name, price) {
    const existing = addItemsCart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        addItemsCart.push({ id, name, price, qty: 1 });
    }
    updateCartUI();
}

function updateCartUI() {
    const container = document.getElementById('posCartItems');
    const countEl = document.getElementById('cartItemCount');
    
    if (addItemsCart.length === 0) {
        container.innerHTML = '<div class="pos-cart-empty">Chưa có món nào</div>';
        countEl.textContent = '0 món';
        return;
    }
    
    let html = '';
    addItemsCart.forEach(item => {
        html += `
            <div class="pos-cart-item">
                <span class="pos-cart-item-name">${item.name}</span>
                <div class="pos-cart-item-qty">
                    <button class="pos-cart-qty-btn" onclick="changeCartItemQty(${item.id}, -1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="pos-cart-qty-num">${item.qty}</span>
                    <button class="pos-cart-qty-btn" onclick="changeCartItemQty(${item.id}, 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        `;
    });
        
    container.innerHTML = html;
    countEl.textContent = addItemsCart.length + ' món';
}

function changeCartItemQty(id, delta) {
    const item = addItemsCart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) {
            addItemsCart = addItemsCart.filter(i => i.id !== id);
        }
        updateCartUI();
    }
}

function submitAddItems() {
    if (addItemsCart.length === 0) {
        alert('Chưa có món nào!');
        return;
    }
    
    const items = addItemsCart.map(i => ({ menu_item_id: i.id, qty: i.qty }));
    
    fetch(BASE_URL + '/orders/add-batch', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: currentOrderId, items: items })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeModal('modalAddItems');
            refreshData();
        } else {
            alert(data.message || 'Lỗi thêm món');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi kết nối');
    });
}

// ── Order Details ────────────────────────────────────────────────────
function openModalDetails(orderId, tableId, tableName, guestCount, total) {
    currentOrderId = orderId;
    currentTableId = tableId;
    
    document.getElementById('detailsTableName').textContent = tableName;
    document.getElementById('posDetailInfo').innerHTML = `
        <span><i class="fas fa-user-friends"></i> <strong>${guestCount}</strong> khách</span>
        <span><i class="fas fa-coins"></i> <strong>${total}</strong></span>
    `;
    
    fetch(BASE_URL + '/orders/get-detail?order_id=' + orderId)
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            orderItemsCache[orderId] = data.items;
            renderDetailItems(data.items);
        }
    });
    
    document.getElementById('modalOrderDetails').classList.add('is-open');
}

function renderDetailItems(items) {
    const container = document.getElementById('posDetailItems');
    
    if (!items || items.length === 0) {
        container.innerHTML = '<div class="pos-cart-empty">Chưa có món nào</div>';
        return;
    }
    
    let html = '';
    items.forEach(item => {
        const statusBadge = item.status === 'confirmed' ? '<span class="status-tag open">Đã xác nhận</span>' : 
                            item.status === 'pending' ? '<span class="status-tag idle">Chờ xác nhận</span>' : '';
        html += `
            <div class="pos-detail-item">
                <div class="pos-detail-item-info">
                    <div class="pos-detail-item-name">${item.item_name} ${statusBadge}</div>
                    ${item.note ? `<div class="pos-detail-item-note"><i class="fas fa-pen"></i> ${item.note}</div>` : ''}
                </div>
                <span class="pos-detail-item-qty">x${item.quantity}</span>
                <span class="pos-detail-item-price">${item.subtotal_fmt || formatPrice(item.item_price * item.quantity)}</span>
                <div class="pos-detail-item-actions">
                    <button class="pos-detail-item-btn" onclick="detailChangeQty(${item.id}, 1)" title="Tăng SL">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="pos-detail-item-btn" onclick="detailChangeQty(${item.id}, -1)" title="Giảm SL">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="pos-detail-item-btn delete" onclick="detailRemoveItem(${item.id})" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function detailChangeQty(itemId, delta) {
    fetch(BASE_URL + '/orders/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId, order_id: currentOrderId, qty_change: delta })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            refreshData();
            fetch(BASE_URL + '/orders/get-detail?order_id=' + currentOrderId)
            .then(r => r.json())
            .then(d => {
                if (d.ok) renderDetailItems(d.items);
            });
        }
    });
}

function detailRemoveItem(itemId) {
    if (!confirm('Xóa món này?')) return;
    
    fetch(BASE_URL + '/orders/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId, order_id: currentOrderId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            refreshData();
            fetch(BASE_URL + '/orders/get-detail?order_id=' + currentOrderId)
            .then(r => r.json())
            .then(d => {
                if (d.ok) renderDetailItems(d.items);
            });
        }
    });
}

// ── Update Guest ─────────────────────────────────────────────────────
function openModalUpdateGuest(orderId, tableId, currentGuest) {
    currentOrderId = orderId;
    currentTableId = tableId;
    document.getElementById('currentGuestDisplay').textContent = currentGuest;
    
    document.querySelectorAll('#modalUpdateGuest .pos-guest-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.guest == currentGuest);
    });
    
    document.getElementById('modalUpdateGuest').classList.add('is-open');
}

function selectUpdateGuest(n) {
    currentGuestCount = n;
    document.querySelectorAll('#modalUpdateGuest .pos-guest-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.guest == n);
    });
}

function submitUpdateGuest() {
    fetch(BASE_URL + '/orders/update-guest-count', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: currentOrderId, guest_count: currentGuestCount })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeModal('modalUpdateGuest');
            refreshData();
        } else {
            alert(data.message || 'Lỗi cập nhật');
        }
    });
}

// ── Payment ──────────────────────────────────────────────────────────
function openModalPayment(orderId, tableId, tableName, amount) {
    currentOrderId = orderId;
    currentTableId = tableId;
    document.getElementById('paymentTableName').textContent = tableName;
    document.getElementById('paymentAmount').textContent = amount;
    document.getElementById('modalPayment').classList.add('is-open');
    document.getElementById('paymentConfirmed').checked = false;
    document.querySelector('.pos-checkbox-row').classList.remove('checked');
}

function selectPaymentMethod(method) {
    currentPaymentMethod = method;
    document.querySelectorAll('.pos-method-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.method === method);
    });
}

function togglePaymentCheckbox() {
    const cb = document.getElementById('paymentConfirmed');
    const row = document.querySelector('.pos-checkbox-row');
    cb.checked = !cb.checked;
    row.classList.toggle('checked', cb.checked);
}

function submitPayment() {
    if (!document.getElementById('paymentConfirmed').checked) {
        alert('Vui lòng xác nhận đã nhận đủ tiền!');
        return;
    }
    
    fetch(BASE_URL + '/tables/close', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table_id: currentTableId,
            order_id: currentOrderId,
            payment_method: currentPaymentMethod
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeModal('modalPayment');
            refreshData();
        } else {
            alert(data.message || 'Lỗi thanh toán');
        }
    });
}

// ── Cancel Table ─────────────────────────────────────────────────────
function cancelTable(orderId, tableId) {
    if (!confirm('Hủy bàn này? (Bàn chưa có món)')) return;
    
    fetch(BASE_URL + '/tables/close', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: tableId, order_id: orderId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            refreshData();
        } else {
            alert(data.message || 'Lỗi hủy bàn');
        }
    });
}

// ── Data Refresh ──────────────────────────────────────────────────────
async function refreshData() {
    if (isRefreshing) return;
    isRefreshing = true;

    const container = document.getElementById('realtimeListContainer');
    const btn = document.querySelector('.refresh-circle-btn');
    
    if (btn) btn.innerHTML = '<i class="fas fa-sync fa-spin"></i>';

    try {
        const res = await fetch(BASE_URL + '/admin/realtime/data?t=' + Date.now(), {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!res.ok) {
            console.error('HTTP Error:', res.status, res.statusText);
            if (container) container.innerHTML = `<div class="pos-loader"><i class="fas fa-exclamation-triangle"></i><h3>Lỗi kết nối (${res.status})</h3><p>Vui lòng kiểm tra lại.</p></div>`;
            return;
        }
        
        const data = await res.json();
        console.log('Realtime data received:', data);
        
        if (data && data.ok) {
            updateStats(data);
            if (Array.isArray(data.data) && data.data.length > 0) {
                renderPOSGrid(data.data);
            } else {
                container.innerHTML = `
                    <div class="pos-loader">
                        <i class="fas fa-utensils fa-3x" style="opacity:0.2"></i>
                        <h3 style="font-weight:800; color:var(--pos-text);">KHÔNG CÓ ORDER</h3>
                        <p style="color:var(--pos-text-muted)">Không có bàn đang phục vụ</p>
                    </div>
                `;
            }
        } else {
            console.error('Invalid data format:', data);
            if (container) container.innerHTML = `<div class="pos-loader"><i class="fas fa-exclamation-triangle"></i><h3>Lỗi dữ liệu</h3><p>${data?.message || 'Không thể tải dữ liệu'}</p></div>`;
        }
    } catch (err) {
        console.error('Lỗi POS Sync:', err);
        if (container) container.innerHTML = `<div class="pos-loader"><i class="fas fa-exclamation-triangle"></i><h3>Lỗi kết nối</h3><p>${err.message || 'Không thể kết nối server'}</p></div>`;
    } finally {
        if (btn) btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
        isRefreshing = false;
        timerCount = 8;
    }
}

function updateStats(data) {
    console.log('Updating stats with:', data);
    if (!data || !data.counts) {
        console.error('Invalid stats data:', data);
        return;
    }
    
    document.getElementById('statOccupied').textContent = data.counts.occupied || 0;
    document.getElementById('statAvailable').textContent = data.counts.available || 0;
    
    let tempTotal = 0;
    if (Array.isArray(data.data)) {
        data.data.forEach(o => { 
            if (o.status === 'open') tempTotal += parseFloat(o.total || 0); 
        });
    }
    document.getElementById('statTempRevenue').textContent = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
    
    // Notifications count - optional
    const notifEl = document.getElementById('statNotif');
    if (notifEl) {
        fetch(BASE_URL + '/notifications/count')
        .then(r => r.json())
        .then(d => {
            if (d && d.ok) notifEl.textContent = d.count || 0;
        })
        .catch(e => console.log('Notifications fetch error:', e));
    }
}

function formatPrice(n) {
    return new Intl.NumberFormat('vi-VN').format(n) + 'đ';
}

function renderPOSGrid(orders) {
    console.log('renderPOSGrid called with:', orders);
    const container = document.getElementById('realtimeListContainer');
    
    if (!container) {
        console.error('Container not found');
        return;
    }
    
    if (!Array.isArray(orders) || orders.length === 0) {
        console.log('No orders to render');
        container.innerHTML = `
            <div class="pos-loader">
                <i class="fas fa-utensils fa-3x" style="opacity:0.2"></i>
                <h3 style="font-weight:800; color:var(--pos-text);">KHÔNG CÓ DỮ LIỆU</h3>
                <p style="color:var(--pos-text-muted)">Hệ thống đang chờ đơn hàng mới...</p>
            </div>
        `;
        return;
    }

    try {
        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const isIdle = (order.is_idle === true || order.is_idle === 1) && !isClosed;
            const totalValue = parseFloat(order.total || 0);
            
            const statusTag = isClosed ? 'closed' : (isIdle ? 'idle' : 'open');
            const statusText = isClosed ? 'Đã thanh toán' : (isIdle ? 'Đang chờ' : 'Đang ăn');
            
            let idleBadge = '';
            if (isIdle) {
                const remaining = Math.max(0, 300 - order.idle_seconds);
                const min = Math.floor(remaining / 60);
                const sec = remaining % 60;
                const color = remaining < 60 ? 'var(--pos-danger)' : 'var(--pos-warning)';
                idleBadge = `<div style="color:${color}; font-weight:800; font-size:0.75rem; margin-top:8px;">
                    <i class="fas fa-clock"></i> HUỶ SAU: ${min}:${sec < 10 ? '0'+sec : sec}
                </div>`;
            }

            let rows = '';
        (order.items || []).forEach(it => {
            rows += `
                <tr>
                    <td>
                        <span class="item-title">${it.item_name || it.name || ''}</span>
                        ${it.note ? `<span class="item-note"><i class="fas fa-exclamation-circle"></i> ${it.note}</span>` : ''}
                    </td>
                    <td class="qty-badge">x${it.quantity}</td>
                    <td class="price-col">${it.subtotal_fmt || formatPrice((it.item_price || 0) * it.quantity)}</td>
                </tr>
            `;
        });

        const safeTableName = (order.full_name || '').replace(/'/g, "\\'");
        
        html += `
            <div class="pos-card" id="card-${order.id}">
                <div class="card-header-pos">
                    <div class="table-main-info">
                        <h2>${order.full_name}</h2>
                        <div class="table-sub-info">
                            <span class="guest-edit" onclick="openModalUpdateGuest(${order.id}, ${order.table_id}, ${order.guest_count})">
                                <i class="fas fa-user-friends me-1"></i> ${order.guest_count} khách <i class="fas fa-pen" style="font-size:0.6rem"></i>
                            </span>
                            <span><i class="fas fa-user-tie me-1"></i> ${order.waiter_name || 'Khách QR'}</span>
                        </div>
                        ${idleBadge}
                    </div>
                    <div class="status-tag ${statusTag}">${statusText}</div>
                </div>
                
                <div class="card-body-pos">
                    <table class="pos-table">
                        <thead>
                            <tr>
                                <th>Món gọi</th>
                                <th>SL</th>
                                <th style="text-align:right">Tạm tính</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
                
                <div class="card-footer-pos">
                    <div class="total-summary">
                        <div class="summary-left">
                            <span>GIỜ MỞ BÀN</span>
                            <span>${order.opened_at_fmt}</span>
                        </div>
                        <div class="summary-right">
                            <span class="total-label">TỔNG CỘNG</span>
                            <span class="total-value">${order.total_fmt}</span>
                        </div>
                    </div>
                    <div class="action-row">
                        ${isClosed 
                            ? `<button onclick="dismissOrder(${order.id})" class="btn-pos btn-pos-primary">
                                <i class="fas fa-archive"></i> LƯU TRỮ
                               </button>`
                            : totalValue === 0
                                ? `<button onclick="cancelTable(${order.id}, ${order.table_id})" class="btn-pos btn-pos-danger">
                                    <i class="fas fa-times"></i> HỦY BÀN
                                   </button>`
                                : `<button onclick="openModalDetails(${order.id}, ${order.table_id}, '${safeTableName}', ${order.guest_count}, '${order.total_fmt}')" class="btn-pos btn-pos-outline">
                                    <i class="fas fa-eye"></i> CHI TIẾT
                                   </button>
                                   <button onclick="openModalAddItems(${order.id}, ${order.table_id}, '${safeTableName}')" class="btn-pos btn-pos-outline">
                                    <i class="fas fa-plus"></i> THÊM MÓN
                                   </button>
                                   <button onclick="openModalPayment(${order.id}, ${order.table_id}, '${safeTableName}', '${order.total_fmt}')" class="btn-pos btn-pos-gold">
                                    <i class="fas fa-credit-card"></i> THANH TOÁN
                                   </button>`
                        }
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    } catch (err) {
        console.error('Lỗi renderPOSGrid:', err);
        container.innerHTML = `<div class="pos-loader"><i class="fas fa-exclamation-triangle"></i><h3>Lỗi hiển thị</h3><p>${err.message}</p></div>`;
    }
}

async function dismissOrder(id) {
    const fd = new FormData();
    fd.append('order_id', id);
    const res = await fetch(BASE_URL + '/admin/realtime/dismiss', { method: 'POST', body: fd });
    if ((await res.json()).ok) {
        const card = document.getElementById(`card-${id}`);
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => refreshData(), 300);
        }
    }
}

setInterval(() => {
    timerCount--;
    if (timerCount <= 0) refreshData();
    const el = document.getElementById('reloadCount');
    if (el) el.textContent = timerCount;
}, 1000);

refreshData();
</script>