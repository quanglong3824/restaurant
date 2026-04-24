<?php // views/menu/index.php — Digital Menu (Compact Layout) ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/menu/index.css">

<div class="page-content pos-layout">

    <!-- MAIN MENU -->
    <div class="pos-menu-col">
        <div class="menu-type-tabs">
            <?php foreach ($menuTypes as $type): ?>
                <a href="<?= BASE_URL ?>/menu?type=<?= e($type['key']) ?><?= $tableId ? '&table_id=' . $tableId : '' ?><?= $orderId ? '&order_id=' . $orderId : '' ?>"
                    class="menu-type-tab <?= $currentType === $type['key'] ? 'is-active' : '' ?>">
                    <i class="fas <?= e($type['icon']) ?>"></i>
                    <?= e($type['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($categories) && $currentType !== 'sets'): ?>
            <div class="category-filter-bar">
                <button class="filter-pill is-active" data-filter="all">Tất cả</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter-pill" data-filter="<?= e($cat['name']) ?>">
                        <?= e($cat['name']) ?>
                        <?php if (!empty($cat['name_en'])): ?>
                            <span style="display:block; font-size:0.6rem; opacity:0.6; font-weight:500; margin-top:-2px;"><?= e($cat['name_en']) ?></span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="menuItemsContainer">
            <?php if ($currentType === 'sets'): ?>
                <!-- Only show Sets & Combo when in Set & Combo tab -->
                <?php if (!empty($sets)): ?>
                    <div class="menu-section" data-section="Sets & Combo">
                        <h3><i class="fas fa-boxes-stacked"></i> SETS & COMBO</h3>
                        <div class="menu-items-grid">
                            <?php foreach ($sets as $set): ?>
                                <div class="list-item-card" style="border:1px solid var(--gold-light); background:#fffcf5;">
                                    <div style="display:flex; flex:1; align-items:center; cursor:pointer; gap:0.65rem;"
                                        onclick="handleOpenSetModal(<?= e(json_encode($set)) ?>)">
                                        <?php if ($set['image']): ?>
                                            <img src="<?= BASE_URL . '/public/uploads/' . e($set['image']) ?>" class="list-item-img" alt="<?= e($set['name']) ?>">
                                        <?php else: ?>
                                            <div class="list-item-img" style="display:flex;align-items:center;justify-content:center;color:var(--gold-dark);">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="list-item-body">
                                            <div class="list-item-name" style="color:var(--gold-dark);">
                                                <?= e($set['name']) ?>
                                                <?php if (!empty($set['name_en'])): ?>
                                                    <div class="list-item-name-en"><?= e($set['name_en']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="list-item-price"><?= formatPrice($set['price']) ?></div>
                                        </div>
                                    </div>
                                    <div class="list-item-action" style="background:var(--gold); color:white;" onclick="handleOpenSetModal(<?= e(json_encode($set)) ?>)">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Show regular menu items -->
                <?php if (empty($grouped)): ?>
                    <div class="empty-state py-5 text-center">
                        <i class="fas fa-mortar-pestle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có món nào trong mục này.</p>
                    </div>
                <?php endif; ?>
                <?php foreach ($grouped as $catName => $items): ?>
                    <div class="menu-section" data-section="<?= e($catName) ?>">
                        <h3>
                            <i class="fas fa-caret-right"></i> <?= e($catName) ?>
                            <?php
                            $catObj = null;
                            foreach ($categories as $c) if ($c['name'] === $catName) { $catObj = $c; break; }
                            if ($catObj && !empty($catObj['name_en'])): ?>
                                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:500; margin-left:5px;">/ <?= e($catObj['name_en']) ?></span>
                            <?php endif; ?>
                        </h3>
                        <div class="menu-items-grid">
                            <?php foreach ($items as $item): ?>
                                <?php
                                    // Tính item_options kết hợp VI và EN để Waiter dễ chọn
                                    $optsStr = $item['note_options'] ?? '';
                                    $optsEnStr = $item['note_options_en'] ?? '';
                                    $itemOptsArr = array_filter(array_map('trim', explode(',', $optsStr)));
                                    $itemOptsEnArr = array_filter(array_map('trim', explode(',', $optsEnStr)));
                                    
                                    $combinedOpts = [];
                                    foreach ($itemOptsArr as $idx => $optVal) {
                                        $enVal = $itemOptsEnArr[$idx] ?? '';
                                        $combinedOpts[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                                    }
                                    $itemOptsData = htmlspecialchars(json_encode($combinedOpts, JSON_UNESCAPED_UNICODE));
                                ?>
                                <div class="list-item-card">
                                    <div style="display:flex; flex:1; align-items:center; cursor:pointer; gap:0.65rem;"
                                        data-id="<?= $item['id'] ?>" data-name="<?= e($item['name']) ?>"
                                        data-price="<?= $item['price'] ?>"
                                        data-img="<?= $item['image'] ? BASE_URL . '/public/uploads/' . e($item['image']) : '' ?>"
                                        data-desc="<?= e($item['description'] ?? '') ?>" data-order="<?= $orderId ?: '' ?>"
                                        data-options="<?= $itemOptsData ?>"
                                        onclick="handleOpenItemModal(this)">
                                        <?php if ($item['image']): ?>
                                            <img src="<?= BASE_URL . '/public/uploads/' . e($item['image']) ?>" class="list-item-img" alt="<?= e($item['name']) ?>">
                                        <?php else: ?>
                                            <div class="list-item-img" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="list-item-body">
                                            <div class="list-item-name">
                                                <?= e($item['name']) ?>
                                                <?php if (!empty($item['name_en'])): ?>
                                                    <div class="list-item-name-en"><?= e($item['name_en']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="list-item-price"><?= formatPrice($item['price']) ?></div>
                                        </div>
                                    </div>
                                    <div class="list-item-action" onclick="quickAdd(event, <?= $item['id'] ?>, <?= $orderId ?: 'null' ?>)">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- CART SIDEBAR - Always Visible for Waiter -->
    <div class="pos-cart-col">
        <div class="cart-panel">
            <div class="cart-header">
                <div style="flex:1;">
                    <h4 style="margin:0; font-size:1.1rem;"><?= $tableId > 0 ? e($tableModel->getFullDisplayName($tableId)) : 'GIỎ HÀNG' ?></h4>
                    <small><?= $orderId > 0 ? e($order['guest_count'] ?? 1) . ' khách' : ($tableId > 0 ? 'Bàn trống' : 'Chưa chọn bàn') ?></small>
                </div>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <?php if ($tableId > 0): ?>
                        <button type="button" class="btn-ghost" style="padding:4px 8px; font-size:0.7rem; background:rgba(0,0,0,0.05); border-radius:4px;" 
                                onclick="window.location.href='<?= BASE_URL ?>/menu?type=<?= $currentType ?>&table_id=0'" title="Đổi bàn">
                            <i class="fas fa-exchange-alt"></i> ĐỔI
                        </button>
                    <?php endif; ?>
                    <?php if ($orderId > 0 && count(array_filter($orderItems, fn($it) => $it['status'] === 'draft')) > 0): ?>
                        <button type="button" class="split-btn" onclick="openSplitModal()" title="Tách bàn">
                            <i class="fas fa-cut"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="cart-body" onclick="handleBodyClick(event)">
                <?php if ($tableId <= 0): ?>
                    <div class="select-table-box p-4 text-center">
                        <div class="mb-3" style="font-size: 2.5rem; color: var(--gold); opacity: 0.5;">
                            <i class="fas fa-chair"></i>
                        </div>
                        <h5 class="mb-3">Vui lòng chọn bàn</h5>
                        <select class="form-control mb-3" onchange="window.location.href='<?= BASE_URL ?>/menu?type=<?= $currentType ?>&table_id=' + this.value">
                            <option value="">-- Chọn bàn --</option>
                            <?php foreach ($tables as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= $tableId == $t['id'] ? 'selected' : '' ?>><?= e($t['name']) ?> (<?= e($t['area']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-muted small">Bạn cần chọn bàn trước khi gọi món.</p>
                    </div>
                <?php elseif (empty($orderItems)): ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Bàn chưa có món</p>
                        <p class="text-muted small">Chọn món để bắt đầu order</p>
                    </div>
                <?php else: ?>
                    <?php 
                        $draftItems     = array_filter($orderItems, fn($it) => $it['status'] === 'draft');
                        $confirmedItems = array_filter($orderItems, fn($it) => $it['status'] === 'confirmed');
                        $pendingItems   = array_filter($orderItems, fn($it) => $it['status'] === 'pending');
                        $draftCount     = count($draftItems);
                    ?>
                    <?php if ($draftCount > 0): ?>
                        <div class="section-label"><i class="fas fa-edit"></i> Món nháp</div>
                        <?php foreach ($draftItems as $it):
                            // Lấy options kết hợp VI và EN cho mục món nháp
                            $itOptsStr = $it['note_options'] ?? '';
                            $itOptsEnStr = $it['note_options_en'] ?? '';
                            $itOptsArr = array_filter(array_map('trim', explode(',', $itOptsStr)));
                            $itOptsEnArr = array_filter(array_map('trim', explode(',', $itOptsEnStr)));
                            
                            $combinedOpts = [];
                            foreach ($itOptsArr as $idx => $optVal) {
                                $enVal = $itOptsEnArr[$idx] ?? '';
                                $combinedOpts[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                            }
                            $itOptsJson = json_encode($combinedOpts, JSON_UNESCAPED_UNICODE);
                            $itNote = trim($it['note'] ?? '');
                        ?>
                            <div class="cart-item-row" data-item-id="<?= $it['id'] ?>">
                                <div style="display:flex; align-items:center; gap:0.5rem; flex:1;">
                                    <div style="flex:1;">
                                        <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                        <?php if ($itNote && !preg_match('/^Set:\s*.+$/', $itNote)): ?>
                                        <div style="display:flex;flex-wrap:wrap;gap:.3rem;margin:.25rem 0;">
                                            <?php foreach (explode(',', $itNote) as $n): $n = trim($n); if ($n): ?>
                                            <span style="background:rgba(212,175,55,.13);color:var(--gold-dark,#785e0a);border-radius:12px;padding:.1rem .45rem;font-size:.68rem;font-weight:700;"><?= e($n) ?></span>
                                            <?php endif; endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <span class="cart-item-price"><?= formatPrice($it['item_price']) ?></span>
                                            <div class="qty-control">
                                                <button onclick="event.stopPropagation(); changeCartQty(<?= $it['id'] ?>, -1)"><i class="fas fa-minus"></i></button>
                                                <span><?= $it['quantity'] ?></span>
                                                <button onclick="event.stopPropagation(); changeCartQty(<?= $it['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                                    <div class="cart-item-price" style="font-size:0.9rem;"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <button onclick="event.stopPropagation(); openCartNoteModal(<?= $it['id'] ?>, <?= htmlspecialchars($itOptsJson) ?>, '<?= addslashes(htmlspecialchars($itNote, ENT_QUOTES)) ?>')"
                                            style="border:none;background:none;color:var(--gold,#d4af37);padding:3px;cursor:pointer;font-size:.85rem;" title="Ghi chú">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <span class="cart-item-status draft">Nháp</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($pendingItems)): ?>
                        <div class="section-label" style="color:var(--warning)"><i class="fas fa-clock"></i> Chờ xác nhận món</div>
                        <?php foreach ($pendingItems as $it): ?>
                            <div class="cart-item-row opacity-75">
                                <div style="flex:1;">
                                    <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                    <span class="cart-item-qty">x<?= $it['quantity'] ?></span>
                                </div>
                                <div style="text-align:right;">
                                    <div class="cart-item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                    <span class="cart-item-status pending">Chờ xác nhận</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($confirmedItems)): ?>
                        <div class="section-label"><i class="fas fa-check-circle"></i> Đã gửi</div>
                        <?php foreach ($confirmedItems as $it): ?>
                            <div class="cart-item-row" data-item-id="<?= $it['id'] ?>">
                                <div style="display:flex; align-items:center; gap:0.5rem; flex:1;">
                                    <input type="checkbox" class="item-select-cb" 
                                            data-item-id="<?= $it['id'] ?>" 
                                            onchange="toggleSplitButton()"
                                            onclick="event.stopPropagation()">
                                    <div style="flex:1;">
                                        <div class="cart-item-name"><?= e($it['item_name']) ?></div>
                                        <span class="cart-item-qty">x<?= $it['quantity'] ?></span>
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <div class="cart-item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></div>
                                    <span class="cart-item-status confirmed">Đã gửi</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="cart-footer">
                <div class="total-row">
                    <span class="total-label">Tổng cộng</span>
                    <span class="total-amount" id="orderTotal"><?= formatPrice($orderTotal) ?></span>
                </div>
                <div id="cartActionBtn" class="d-flex flex-column gap-2">
                    <?php if ($tableId > 0): ?>
                        <?php $draftCount = $orderId > 0 ? count(array_filter($orderItems, fn($it) => $it['status'] === 'draft')) : 0; ?>
                        <?php if ($draftCount > 0): ?>
                            <button type="button" onclick="confirmOrderAjax(<?= $orderId ?>)" class="cart-action-btn gold">
                                <i class="fas fa-check-circle"></i> XÁC NHẬN MÓN (<?= $draftCount ?>)
                            </button>
                        <?php elseif ($orderId > 0 && !empty($orderItems)): ?>
                            <a href="<?= BASE_URL ?>/orders?table_id=<?= $tableId ?>&order_id=<?= $orderId ?>" class="cart-action-btn success">
                                <i class="fas fa-check-circle"></i> XEM BILL
                            </a>
                        <?php else: ?>
                            <button disabled class="cart-action-btn ghost w-100">BÀN CHƯA CÓ MÓN</button>
                        <?php endif; ?>
                        
                        <button type="button" id="splitTableBtn" onclick="openSplitModal()" class="cart-action-btn" style="background:#dc3545; color:white; display:none; border:none;">
                            <i class="fas fa-cut"></i> TÁCH BÀN (<span id="selectedCount">0</span>)
                        </button>
                    <?php else: ?>
                        <button disabled class="cart-action-btn ghost w-100">CHƯA CHỌN BÀN</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addToast" class="add-toast"></div>

<!-- Set Modal -->
<div class="modal-backdrop" id="modalSetDetail">
    <div class="modal modal-premium" style="max-width:500px;">
        <div class="modal-header">
            <div class="d-flex flex-column">
                <h3 id="modalSetName" class="mb-1"></h3>
                <div id="modalSetPrice" class="text-gold fw-800"></div>
            </div>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalSetDesc" class="text-muted small mb-3"></p>
            <h4 class="form-label mb-3"><i class="fas fa-list-check me-2"></i> Thành phần</h4>
            <div id="modalSetItemsList" class="d-flex flex-column gap-2 mb-4"></div>
            <div class="d-grid gap-2">
                <button onclick="confirmAddSetToOrder()" class="btn-gold w-100">THÊM COMBO</button>
                <button class="btn-ghost w-100" data-modal-close>ĐÓNG</button>
            </div>
        </div>
    </div>
</div>

<!-- Item Modal -->
<div class="modal-backdrop" id="modalItemDetail">
    <div class="modal modal-premium" style="max-width:450px;">
        <div class="modal-header p-0 border-0" style="height:180px; position:relative;" id="modalItemImgContainer">
            <div id="modalItemImg" style="width:100%; height:100%;">
                <div id="modalItemImgPlaceholder" class="d-flex align-items-center justify-content-center h-100 bg-light text-muted opacity-30">
                    <i class="fas fa-image fa-2x"></i>
                </div>
            </div>
            <button class="modal-close" data-modal-close style="position:absolute; top:0.75rem; right:0.75rem; background:rgba(0,0,0,0.4); color:#fff;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body pt-4">
            <h3 id="modalItemName" class="mb-1"></h3>
            <div id="modalItemPrice" class="text-gold fw-800 mb-3"></div>
            <p id="modalItemDesc" class="text-muted small mb-3"></p>

            <div id="orderControlsSection" style="display:none;">
                <!-- Chip options từ admin (hiện khi món có options) -->
                <div id="modalItemOptsWrap" style="display:none; margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:.72rem; color:var(--text-muted); letter-spacing:.5px;">TÙY CHỌN</label>
                    <div id="modalItemOptsContainer" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.3rem;"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:.72rem; color:var(--text-muted); letter-spacing:.5px;">GHI CHÚ THÊM</label>
                    <input type="text" id="modalItemNote" class="form-control" placeholder="Không hành phi, ít cay..." style="margin-top:.3rem;">
                </div>
                <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                    <div class="qty-control" style="padding:4px 10px;">
                        <button onclick="changeModalQty(-1)"><i class="fas fa-minus"></i></button>
                        <span id="modalItemQty" style="width:24px;">1</span>
                        <button onclick="changeModalQty(1)"><i class="fas fa-plus"></i></button>
                    </div>
                    <button onclick="confirmAddToOrder()" class="btn-gold flex-fill">
                        GỌI MÓN: <span id="modalBtnTotal"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const MENU_CONFIG = {
    baseUrl: '<?= BASE_URL ?>',
    orderId: <?= $orderId ?: 0 ?>,
    tableId: <?= $tableId ?: 0 ?>
};
</script>

<!-- Split Table Modal -->
<div class="modal-backdrop" id="modalSplitTable">
    <div class="modal modal-premium" style="max-width:600px;">
        <div class="modal-header">
            <h3><i class="fas fa-cut me-2"></i> TÁCH BÀN</h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-chair me-1"></i> CHỌN BÀN ĐỂ TÁCH
                </label>
                <select id="splitTargetTable" class="form-control" style="width:100%; padding:0.75rem; font-size:0.9rem;">
                    <option value="">-- Chọn bàn mới --</option>
                    <?php foreach ($tables as $t): ?>
                        <?php if ($t['id'] != $tableId && $t['status'] == 'available'): ?>
                        <option value="<?= $t['id'] ?>">
                            <?= e($t['name']) ?> <?= !empty($t['area']) ? '(' . e($t['area']) . ')' : '' ?>
                        </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <option value="new">-- Tạo bàn mới --</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-list me-1"></i> MÓN SẼ TÁCH (<span id="splitItemCount">0</span>)
                </label>
                <div id="splitItemsList" style="max-height:200px; overflow-y:auto; border:1px solid var(--border); border-radius:6px; padding:0.5rem;">
                    <!-- Filled by JS -->
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button onclick="confirmSplitTable()" class="btn-gold flex-fill">
                    <i class="fas fa-cut me-1"></i> TÁCH BÀN
                </button>
                <button class="btn-ghost" data-modal-close>
                    <i class="fas fa-times me-1"></i> HỦY
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ghi chú item trong giỏ hàng -->
<div class="modal-backdrop" id="modalCartNote" style="display:none;">
    <div class="modal modal-premium" style="max-width:400px;">
        <div class="modal-header">
            <h3><i class="fas fa-edit me-2"></i> Ghi chú món</h3>
            <button class="modal-close" type="button" onclick="closeCartNoteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:.85rem;">
            <!-- Chip options -->
            <div id="cartNoteOptsWrap" style="display:none;">
                <label style="font-size:.7rem;color:var(--text-muted);letter-spacing:.5px;font-weight:700;">TÙY CHỌN</label>
                <div id="cartNoteOptsContainer" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.35rem;"></div>
            </div>
            <!-- Free text -->
            <div>
                <label style="font-size:.7rem;color:var(--text-muted);letter-spacing:.5px;font-weight:700;">GHI CHÚ THÊM</label>
                <input type="text" id="cartNoteText" class="form-control" placeholder="Không hành phi, chín kỹ..." maxlength="120" style="margin-top:.3rem;">
            </div>
            <button type="button" class="btn-gold w-100" onclick="submitCartNote()" id="btnSaveCartNote" style="padding:.75rem;font-size:.95rem;">
                <i class="fas fa-check me-2"></i> Lưu ghi chú
            </button>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/menu/index.js?v=<?= time() ?>" defer></script>
<script>
// ── Cart Note Modal ────────────────────────────────────────────────
let _cartNoteItemId = 0, _cartNoteOpts = [], _cartNoteSelectedOpts = [];

function openCartNoteModal(itemId, opts, currentNote) {
    _cartNoteItemId     = itemId;
    _cartNoteOpts       = (typeof opts === 'string') ? JSON.parse(opts || '[]') : (opts || []);
    _cartNoteSelectedOpts = [];

    // Phân tách note hiện tại
    const currentParts = currentNote ? currentNote.split(',').map(s => s.trim()).filter(Boolean) : [];
    const selectedOpts = currentParts.filter(p => _cartNoteOpts.includes(p));
    const freeText     = currentParts.filter(p => !_cartNoteOpts.includes(p)).join(', ');

    _cartNoteSelectedOpts = [...selectedOpts];
    document.getElementById('cartNoteText').value = freeText;

    // Render chips
    const wrap = document.getElementById('cartNoteOptsWrap');
    const container = document.getElementById('cartNoteOptsContainer');
    container.innerHTML = '';
    if (_cartNoteOpts.length > 0) {
        wrap.style.display = 'block';
        _cartNoteOpts.forEach(opt => {
            const isActive = selectedOpts.includes(opt);
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = opt;
            chip.style.cssText = `padding:.28rem .65rem;border-radius:20px;font-size:.8rem;cursor:pointer;transition:all .18s;border:1.5px solid ${isActive ? 'var(--gold,#d4af37)' : '#e2e8f0'};background:${isActive ? 'rgba(212,175,55,.15)' : '#f8fafc'};color:${isActive ? 'var(--gold-dark,#785e0a)' : '#64748b'};font-weight:${isActive ? '700' : '500'};`;
            chip.onclick = () => {
                const idx = _cartNoteSelectedOpts.indexOf(opt);
                if (idx >= 0) {
                    _cartNoteSelectedOpts.splice(idx, 1);
                    chip.style.background = '#f8fafc'; chip.style.borderColor = '#e2e8f0';
                    chip.style.color = '#64748b'; chip.style.fontWeight = '500';
                } else {
                    _cartNoteSelectedOpts.push(opt);
                    chip.style.background = 'rgba(212,175,55,.15)'; chip.style.borderColor = 'var(--gold,#d4af37)';
                    chip.style.color = 'var(--gold-dark,#785e0a)'; chip.style.fontWeight = '700';
                }
            };
            container.appendChild(chip);
        });
    } else {
        wrap.style.display = 'none';
    }

    document.getElementById('modalCartNote').style.display = 'flex';
    setTimeout(() => document.getElementById('cartNoteText').focus(), 150);
}

function closeCartNoteModal() {
    document.getElementById('modalCartNote').style.display = 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('modalCartNote').addEventListener('click', function(e) {
        if (e.target === this) closeCartNoteModal();
    });
});

function submitCartNote() {
    const freeText = document.getElementById('cartNoteText').value.trim();
    const parts = [..._cartNoteSelectedOpts];
    if (freeText) parts.push(freeText);
    const note = parts.join(', ');

    const btn = document.getElementById('btnSaveCartNote');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang lưu...';

    fetch('<?= BASE_URL ?>/orders/update-note', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: _cartNoteItemId, order_id: MENU_CONFIG.orderId, note })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeCartNoteModal();
            updateCartUI(data);
        } else {
            alert(data.message || 'Lỗi lưu ghi chú');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i> Lưu ghi chú';
        }
    })
    .catch(() => {
        alert('Lỗi kết nối!');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i> Lưu ghi chú';
    });
}
</script>
