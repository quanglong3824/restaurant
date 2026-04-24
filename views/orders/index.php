<?php
// views/orders/index.php — Premium Order Detail View

$draftItems = [];
$confirmedItems = [];
$pendingItems = [];
$hasDraft = false;
$isSplitAction = (isset($_GET['action']) && $_GET['action'] === 'split');

if (!empty($items)) {
    foreach ($items as $item) {
        $status = $item['status'] ?? 'draft';
        if ($status === 'confirmed' || $status === 'cooking' || $status === 'served') {
            $confirmedItems[] = $item;
        } elseif ($status === 'pending') {
            $pendingItems[] = $item;
        } else {
            $draftItems[] = $item;
            $hasDraft = true;
        }
    }
}
?>

<div class="page-content">

    <?php if (!$order): ?>
        <div class="empty-items-state">
            <i class="fas fa-receipt"></i>
            <p>Bàn chưa có order</p>
            <a href="<?= BASE_URL ?>/orders" class="bill-btn primary">
                <i class="fas fa-arrow-left"></i> XEM DANH SÁCH ORDER
            </a>
        </div>
    <?php else: ?>

        <!-- Table Header -->
        <div class="table-header-card">
            <div class="table-badge">
                <span class="table-badge-num"><?= e(str_replace(['Bàn ', 'Phòng '], '', $table['name'])) ?></span>
            </div>
            <div class="table-info">
                <h1 class="table-title">
                    <?= e($table_display_name) ?>
                    <?php if (($order['order_source'] ?? '') === 'customer_qr'): ?>
                        <span class="qr-source-badge">
                            <i class="fas fa-qrcode"></i> KHÁCH QUÉT QR
                        </span>
                    <?php endif; ?>
                </h1>
                <div class="table-meta">
                    <span class="meta-tag">
                        <i class="fas fa-clock"></i>
                        <?= date('H:i', strtotime($order['opened_at'])) ?>
                    </span>
                    <span class="meta-tag clickable" onclick="Aurora.openModal('modalUpdateGuestCount')">
                        <i class="fas fa-user-friends"></i>
                        <span id="displayGuestCount"><?= $order['guest_count'] ?> khách</span>
                        <i class="fas fa-pen" style="font-size:0.6rem;opacity:0.5;"></i>
                    </span>
                    <span class="meta-tag">
                        <i class="fas fa-user"></i>
                        <?= e($order['waiter_name']) ?>
                    </span>
                </div>
                <?php if (!empty($order['note'])): ?>
                    <div class="order-note-banner" style="margin-top:10px;padding:8px 12px;">
                        <i class="fas fa-info-circle"></i>
                        <?= e($order['note']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="table-actions">
                <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>" class="btn-add-item">
                    <i class="fas fa-plus"></i> THÊM MÓN
                </a>
            </div>
        </div>

        <!-- Merge Suggestion -->
        <?php if (!empty($mergeSuggestion)): ?>
            <div class="order-note-banner" style="background:#fef3c7;border-color:#fde68a;color:#92400e;">
                <i class="fas fa-exclamation-triangle"></i>
                <?= $mergeSuggestion ?>
                <button onclick="Aurora.openModal('modalMergeAreaFromOrder')" class="bill-btn secondary" style="padding:6px 12px;font-size:0.7rem;">
                    <i class="fas fa-object-group"></i> Ghép bàn
                </button>
            </div>
        <?php endif; ?>

        <!-- Split Mode Banner -->
        <?php if ($isSplitAction): ?>
            <div class="split-mode-banner">
                <i class="fas fa-cut"></i>
                <div class="split-mode-content">
                    <h5>CHẾ ĐỘ TÁCH BÀN / CHUYỂN MÓN</h5>
                    <p>Chọn các món muốn tách sang bàn mới</p>
                </div>
                <a href="<?= BASE_URL ?>/orders?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>" class="bill-btn secondary" style="padding:8px 14px;font-size:0.8rem;">
                    HỦY
                </a>
            </div>
        <?php endif; ?>

        <!-- Items Container -->
        <div class="items-container">

            <!-- Pending Items (QR Customer) -->
            <?php if (!empty($pendingItems)): ?>
                <div class="section-card">
                    <div class="section-header pending">
                        <div class="section-icon pending"><i class="fas fa-clock"></i></div>
                        <span class="section-title pending">CHỜ XÁC NHẬN (QR khách gửi)</span>
                    </div>
                    <div class="section-body">
                        <?php foreach ($pendingItems as $item): ?>
                            <div class="item-row pending <?= $isSplitAction ? 'split-selectable' : '' ?>" onclick="<?= $isSplitAction ? 'toggleSplitItem(' . $item['id'] . ')' : '' ?>">
                                <?php if ($isSplitAction): ?>
                                    <div class="split-checkbox">
                                        <input type="checkbox" name="split_items[]" value="<?= $item['id'] ?>" onclick="event.stopPropagation(); updateSplitCount();">
                                    </div>
                                <?php endif; ?>
                                <div class="item-info">
                                    <div class="item-name"><?= e($item['item_name']) ?></div>
                                    <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                        <div class="item-note-chips">
                                            <?php foreach (explode(',', $item['note']) as $n): $n = trim($n); if ($n): ?>
                                                <span class="note-chip"><?= e($n) ?></span>
                                            <?php endif; endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="qty-badge">x<?= $item['quantity'] ?></div>
                                <div class="item-price">
                                    <div class="item-price-amount"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                </div>
                                <?php if (!$isSplitAction): ?>
                                    <div class="item-actions">
                                        <?php
                                        $optsStr = $item['note_options'] ?? '';
                                        $optsEnStr = $item['note_options_en'] ?? '';
                                        $itOptsArr = array_filter(array_map('trim', explode(',', $optsStr)));
                                        $itOptsEnArr = array_filter(array_map('trim', explode(',', $optsEnStr)));
                                        $combinedOpts = [];
                                        foreach ($itOptsArr as $idx => $optVal) {
                                            $enVal = $itOptsEnArr[$idx] ?? '';
                                            $combinedOpts[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                                        }
                                        $itemOptsJson = json_encode($combinedOpts, JSON_UNESCAPED_UNICODE);
                                        ?>
                                        <button class="action-btn edit" onclick="event.stopPropagation(); openNoteModal(<?= $item['id'] ?>, <?= $order['id'] ?>, <?= $itemOptsJson ?>, '<?= addslashes(e($item['item_name'])) ?>', '<?= addslashes(e($item['note'] ?? '')) ?>')" title="Ghi chú">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="action-btn delete" onclick="event.stopPropagation(); removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <span class="item-status-badge pending"><i class="fas fa-clock"></i> Chờ xác nhận</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Draft Items -->
            <?php if (!empty($draftItems)): ?>
                <div class="section-card">
                    <div class="section-header draft">
                        <div class="section-icon draft"><i class="fas fa-edit"></i></div>
                        <span class="section-title draft">MÓN ĐANG CHỌN (Nháp)</span>
                    </div>
                    <div class="section-body">
                        <?php
                        $groupedDraft = [];
                        foreach ($draftItems as $item) {
                            $setNote = '';
                            if (preg_match('/^Set:\s*(.+)$/', $item['note'] ?? '', $matches)) $setNote = $matches[1];
                            $groupedDraft[$setNote][] = $item;
                        }
                        foreach ($groupedDraft as $setNote => $setItems):
                            if ($setNote): ?>
                                <div class="set-group-label">
                                    <span class="set-badge"><i class="fas fa-layer-group"></i> <?= e($setNote) ?></span>
                                </div>
                            <?php endif;
                            foreach ($setItems as $item): ?>
                                <div class="item-row draft <?= $isSplitAction ? 'split-selectable' : '' ?>" onclick="<?= $isSplitAction ? 'toggleSplitItem(' . $item['id'] . ')' : '' ?>">
                                    <?php if ($isSplitAction): ?>
                                        <div class="split-checkbox">
                                            <input type="checkbox" name="split_items[]" value="<?= $item['id'] ?>" onclick="event.stopPropagation(); updateSplitCount();">
                                        </div>
                                    <?php endif; ?>
                                    <div class="item-info">
                                        <div class="item-name"><?= e($item['item_name']) ?></div>
                                        <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                            <div class="item-note-chips">
                                                <?php foreach (explode(',', $item['note']) as $n): $n = trim($n); if ($n): ?>
                                                    <span class="note-chip"><?= e($n) ?></span>
                                                <?php endif; endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!$isSplitAction): ?>
                                        <div class="item-qty-controls">
                                            <button class="qty-btn" onclick="event.stopPropagation(); changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, -1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="qty-display" id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                                            <button class="qty-btn" onclick="event.stopPropagation(); changeQty(<?= $item['id'] ?>, <?= $order['id'] ?>, 1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="qty-badge">x<?= $item['quantity'] ?></div>
                                    <?php endif; ?>
                                    <div class="item-price">
                                        <div class="item-price-amount"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                    </div>
                                    <?php if (!$isSplitAction): ?>
                                        <?php
                                        $optsStr2 = $item['note_options'] ?? '';
                                        $optsEnStr2 = $item['note_options_en'] ?? '';
                                        $itOptsArr2 = array_filter(array_map('trim', explode(',', $optsStr2)));
                                        $itOptsEnArr2 = array_filter(array_map('trim', explode(',', $optsEnStr2)));
                                        $combinedOpts2 = [];
                                        foreach ($itOptsArr2 as $idx => $optVal) {
                                            $enVal = $itOptsEnArr2[$idx] ?? '';
                                            $combinedOpts2[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                                        }
                                        $itemOptsJson2 = json_encode($combinedOpts2, JSON_UNESCAPED_UNICODE);
                                        ?>
                                        <div class="item-actions">
                                            <button class="action-btn edit" onclick="event.stopPropagation(); openNoteModal(<?= $item['id'] ?>, <?= $order['id'] ?>, <?= $itemOptsJson2 ?>, '<?= addslashes(e($item['item_name'])) ?>', '<?= addslashes(e($item['note'] ?? '')) ?>')" title="Ghi chú">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="event.stopPropagation(); removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <span class="item-status-badge draft"><i class="fas fa-edit"></i> Nháp</span>
                                </div>
                            <?php endforeach;
                        endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Confirmed items -->
            <?php if (!empty($confirmedItems)): ?>
                <div class="section-card">
                    <div class="section-header confirmed">
                        <div class="section-icon confirmed"><i class="fas fa-check-circle"></i></div>
                        <span class="section-title confirmed">ĐÃ XÁC NHẬN (Đang làm)</span>
                    </div>
                    <div class="section-body">
                        <?php
                        $groupedConfirmed = [];
                        foreach ($confirmedItems as $item) {
                            $setNote = '';
                            if (preg_match('/^Set:\s*(.+)$/', $item['note'] ?? '', $matches)) $setNote = $matches[1];
                            $groupedConfirmed[$setNote][] = $item;
                        }
                        foreach ($groupedConfirmed as $setNote => $setItems):
                            if ($setNote): ?>
                                <div class="set-group-label">
                                    <span class="set-badge"><i class="fas fa-layer-group"></i> <?= e($setNote) ?></span>
                                </div>
                            <?php endif;
                            foreach ($setItems as $item):
                                $itemStatus = $item['status'] ?? 'confirmed';
                                $statusIcon = $itemStatus === 'cooking' ? 'fa-fire' : ($itemStatus === 'served' ? 'fa-check' : 'fa-check-circle');
                                $statusText = $itemStatus === 'cooking' ? 'Đang nấu' : ($itemStatus === 'served' ? 'Đã phục vụ' : 'Đã xác nhận');
                            ?>
                                <div class="item-row confirmed <?= $isSplitAction ? 'split-selectable' : '' ?>" onclick="<?= $isSplitAction ? 'toggleSplitItem(' . $item['id'] . ')' : '' ?>">
                                    <?php if ($isSplitAction): ?>
                                        <div class="split-checkbox">
                                            <input type="checkbox" name="split_items[]" value="<?= $item['id'] ?>" onclick="event.stopPropagation(); updateSplitCount();">
                                        </div>
                                    <?php endif; ?>
                                    <div class="item-info">
                                        <div class="item-name"><?= e($item['item_name']) ?></div>
                                        <?php if ($item['note'] && !preg_match('/^Set:\s*.+$/', $item['note'])): ?>
                                            <div class="item-note-chips">
                                                <?php foreach (explode(',', $item['note']) as $n): $n = trim($n); if ($n): ?>
                                                    <span class="note-chip"><?= e($n) ?></span>
                                                <?php endif; endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="qty-badge">x<?= $item['quantity'] ?></div>
                                    <div class="item-price">
                                        <div class="item-price-amount"><?= formatPrice($item['item_price'] * $item['quantity']) ?></div>
                                    </div>
                                    <?php if (!$isSplitAction): ?>
                                        <?php
                                        $optsStr3 = $item['note_options'] ?? '';
                                        $optsEnStr3 = $item['note_options_en'] ?? '';
                                        $itOptsArr3 = array_filter(array_map('trim', explode(',', $optsStr3)));
                                        $itOptsEnArr3 = array_filter(array_map('trim', explode(',', $optsEnStr3)));
                                        $combinedOpts3 = [];
                                        foreach ($itOptsArr3 as $idx => $optVal) {
                                            $enVal = $itOptsEnArr3[$idx] ?? '';
                                            $combinedOpts3[] = $enVal ? $optVal . ' / ' . $enVal : $optVal;
                                        }
                                        $itemOptsJson3 = json_encode($combinedOpts3, JSON_UNESCAPED_UNICODE);
                                        ?>
                                        <div class="item-actions">
                                            <button class="action-btn edit" onclick="event.stopPropagation(); openNoteModal(<?= $item['id'] ?>, <?= $order['id'] ?>, <?= $itemOptsJson3 ?>, '<?= addslashes(e($item['item_name'])) ?>', '<?= addslashes(e($item['note'] ?? '')) ?>')" title="Ghi chú">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="event.stopPropagation(); removeItem(<?= $item['id'] ?>, <?= $order['id'] ?>)" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <span class="item-status-badge confirmed"><i class="fas <?= $statusIcon ?>"></i> <?= $statusText ?></span>
                                </div>
                            <?php endforeach;
                        endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Empty -->
            <?php if (empty($items)): ?>
                <div class="empty-items-state">
                    <i class="fas fa-utensils"></i>
                    <p>Chưa có món nào được chọn</p>
                    <a href="<?= BASE_URL ?>/menu?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>" class="bill-btn primary">
                        <i class="fas fa-plus"></i> CHỌN MÓN NGAY
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bill Footer -->
        <div class="bill-footer-bar">
            <div class="bill-card">
                <?php if ($isSplitAction): ?>
                    <div class="bill-header">
                        <div>
                            <div class="bill-total-label">Đã chọn để tách</div>
                            <div class="bill-total-amount" id="splitCount">0 món</div>
                        </div>
                    </div>
                    <button class="bill-btn primary" onclick="openConfirmSplitModal()">
                        <i class="fas fa-cut"></i> XÁC NHẬN TÁCH MÓN
                    </button>
                <?php else: ?>
                    <div class="bill-header">
                        <div>
                            <div class="bill-total-label">Tổng tạm tính</div>
                            <div class="bill-total-amount" id="orderTotal"><?= formatPrice($total) ?></div>
                        </div>
                        <div class="bill-vat-note">Đã bao gồm VAT</div>
                    </div>
                    <div class="bill-actions">
                        <?php if ($hasDraft): ?>
                            <form method="POST" action="<?= BASE_URL ?>/orders/confirm" style="width:100%;">
                                <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" class="bill-btn primary pulse-animation">
                                    <i class="fas fa-check-circle"></i> XÁC NHẬN MÓN
                                </button>
                            </form>
                        <?php endif; ?>
                        <?php if ($total > 0): ?>
                            <div class="bill-btn-row">
                                <button class="bill-btn success" onclick="confirmPayment(<?= $table['id'] ?>, <?= $order['id'] ?>, <?= $total ?>)">
                                    <i class="fas fa-credit-card"></i> THANH TOÁN
                                </button>
                                <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="bill-btn secondary">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <button class="bill-btn danger" onclick="confirmClose(<?= $table['id'] ?>, <?= $order['id'] ?>)">
                                <i class="fas fa-door-closed"></i> ĐÓNG BÀN
                            </button>
                        <?php endif; ?>
                        <?php if ($total > 0): ?>
                            <div class="bill-split-link">
                                <a href="<?= BASE_URL ?>/orders?table_id=<?= $table['id'] ?>&order_id=<?= $order['id'] ?>&action=split">
                                    <i class="fas fa-cut"></i> Tách bàn / Chuyển món
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Modal: Payment -->
<div class="modal-backdrop" id="modalClose">
    <div class="modal modal-premium" style="max-width:400px;">
        <div class="modal-header">
            <h3><i class="fas fa-credit-card me-2"></i> Thanh toán</h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="text-align:center; margin-bottom:24px;">
                <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; letter-spacing:1px;">CẦN THANH TOÁN</div>
                <div style="font-size:2rem; font-weight:800; color:var(--gold-dark); font-family:'Outfit'; margin-top:8px;" id="modalTotalAmount"><?= formatPrice($total) ?></div>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/tables/close" id="formCloseTable">
                <input type="hidden" name="table_id" id="closeTableId">
                <input type="hidden" name="order_id" id="closeOrderId">
                
                <div style="margin-bottom:20px;">
                    <div style="font-size:0.65rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; margin-bottom:10px;">PHƯƠNG THỨC</div>
                    <div style="display:flex; gap:10px;">
                        <label id="methodCash" style="flex:1; padding:14px; background:#f8fafc; border:2px solid var(--gold); border-radius:var(--radius); display:flex; flex-direction:column; align-items:center; gap:6px; cursor:pointer;">
                            <input type="radio" name="payment_method" value="cash" checked onchange="updatePaymentMethodUI('cash')" style="display:none">
                            <i class="fas fa-money-bill-wave" style="font-size:1.2rem; color:var(--gold-dark);"></i>
                            <span style="font-size:0.75rem; font-weight:700; color:var(--gold-dark);">TIỀN MẶT</span>
                        </label>
                        <label id="methodTransfer" style="flex:1; padding:14px; background:#f8fafc; border:2px solid #e5e5e5; border-radius:var(--radius); display:flex; flex-direction:column; align-items:center; gap:6px; cursor:pointer;">
                            <input type="radio" name="payment_method" value="transfer" onchange="updatePaymentMethodUI('transfer')" style="display:none">
                            <i class="fas fa-university" style="font-size:1.2rem; color:#8a8a8a;"></i>
                            <span style="font-size:0.75rem; font-weight:700; color:#8a8a8a;">CHUYỂN KHOẢN</span>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <div style="font-size:0.65rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; margin-bottom:10px;">XÁC NHẬN</div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div id="cardPaid" onclick="togglePaymentCheck('checkPaid')" style="padding:12px 16px; background:#f8fafc; border:2px solid #e5e5e5; border-radius:var(--radius); display:flex; align-items:center; gap:12px; cursor:pointer;">
                            <div style="width:32px; height:32px; background:#fff; border:2px solid #e5e5e5; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-check" style="color:#8a8a8a;"></i>
                            </div>
                            <div>
                                <div style="font-size:0.85rem; font-weight:700; color:var(--text);">Đã nhận đủ tiền</div>
                                <div style="font-size:0.65rem; color:#8a8a8a;">Xác nhận thanh toán</div>
                            </div>
                            <input type="checkbox" id="checkPaid" required style="display:none">
                        </div>
                        <div id="cardPrint" onclick="togglePaymentCheck('checkPrintBill')" style="padding:12px 16px; background:#f8fafc; border:2px solid #e5e5e5; border-radius:var(--radius); display:flex; align-items:center; gap:12px; cursor:pointer;">
                            <div style="width:32px; height:32px; background:#fff; border:2px solid #e5e5e5; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-print" style="color:#8a8a8a;"></i>
                            </div>
                            <div>
                                <div style="font-size:0.85rem; font-weight:700; color:var(--text);">Xem hóa đơn</div>
                                <div style="font-size:0.65rem; color:#8a8a8a;">Sau khi hoàn tất</div>
                            </div>
                            <input type="checkbox" id="checkPrintBill" style="display:none">
                        </div>
                    </div>
                </div>

                <button type="button" id="btnSubmitPayment" class="bill-btn primary" onclick="handleSubmitPayment(event)" style="width:100%;">
                    <i class="fas fa-check-circle"></i> HOÀN TẤT THANH TOÁN
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function updatePaymentMethodUI(method) {
    const cash = document.getElementById('methodCash');
    const transfer = document.getElementById('methodTransfer');
    if (method === 'cash') {
        cash.style.borderColor = 'var(--gold)';
        cash.style.background = 'rgba(197,160,89,0.08)';
        cash.querySelector('i').style.color = 'var(--gold-dark)';
        cash.querySelector('span').style.color = 'var(--gold-dark)';
        transfer.style.borderColor = '#e5e5e5';
        transfer.style.background = '#f8fafc';
        transfer.querySelector('i').style.color = '#8a8a8a';
        transfer.querySelector('span').style.color = '#8a8a8a';
    } else {
        transfer.style.borderColor = 'var(--gold)';
        transfer.style.background = 'rgba(197,160,89,0.08)';
        transfer.querySelector('i').style.color = 'var(--gold-dark)';
        transfer.querySelector('span').style.color = 'var(--gold-dark)';
        cash.style.borderColor = '#e5e5e5';
        cash.style.background = '#f8fafc';
        cash.querySelector('i').style.color = '#8a8a8a';
        cash.querySelector('span').style.color = '#8a8a8a';
    }
}
function togglePaymentCheck(id) {
    const cb = document.getElementById(id);
    const card = id === 'checkPaid' ? document.getElementById('cardPaid') : document.getElementById('cardPrint');
    cb.checked = !cb.checked;
    if (cb.checked) {
        card.style.borderColor = id === 'checkPaid' ? '#10b981' : 'var(--gold)';
        card.style.background = id === 'checkPaid' ? '#d1fae5' : 'rgba(197,160,89,0.08)';
        card.querySelector('div:first-child').style.background = id === 'checkPaid' ? '#10b981' : 'var(--gold)';
        card.querySelector('div:first-child').style.borderColor = id === 'checkPaid' ? '#10b981' : 'var(--gold)';
        card.querySelector('i').style.color = '#fff';
    } else {
        card.style.borderColor = '#e5e5e5';
        card.style.background = '#f8fafc';
        card.querySelector('div:first-child').style.background = '#fff';
        card.querySelector('div:first-child').style.borderColor = '#e5e5e5';
        card.querySelector('i').style.color = '#8a8a8a';
    }
}
</script>

<!-- Modal: Guest Count -->
<div class="modal-backdrop" id="modalUpdateGuestCount">
    <div class="modal modal-premium" style="max-width:360px;">
        <div class="modal-header">
            <h3><i class="fas fa-user-friends me-2"></i> Số khách</h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <form id="formUpdateGuestCount" class="modal-body">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?? '' ?>">
            <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:16px;">Chọn số lượng khách tại bàn</div>
            <div style="display:grid; grid-template-columns:repeat(6, 1fr); gap:8px; margin-bottom:16px;">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <label style="padding:10px; background:<?= $i == ($order['guest_count'] ?? 1) ? 'var(--gold)' : '#f8fafc' ?>; border:2px solid <?= $i == ($order['guest_count'] ?? 1) ? 'var(--gold)' : '#e5e5e5' ?>; border-radius:var(--radius); text-align:center; cursor:pointer; transition:all 0.15s;">
                        <input type="radio" name="guest_count_radio" value="<?= $i ?>" <?= $i == ($order['guest_count'] ?? 1) ? 'checked' : '' ?> style="display:none" onchange="this.parentElement.style.background='var(--gold)'; this.parentElement.style.borderColor='var(--gold)'; this.parentElement.style.color='#fff';">
                        <span style="font-size:0.9rem; font-weight:700; color:<?= $i == ($order['guest_count'] ?? 1) ? '#fff' : 'var(--text)' ?>;"><?= $i ?></span>
                    </label>
                <?php endfor; ?>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-size:0.75rem; font-weight:700;">Hoặc nhập:</span>
                <input type="number" name="guest_count_input" style="flex:1; padding:10px; border:2px solid #e5e5e5; border-radius:var(--radius); font-size:0.9rem; font-weight:700;" min="1" value="<?= $order['guest_count'] ?? 1 ?>">
            </div>
            <button type="button" onclick="submitGuestCountUpdate()" class="bill-btn primary" style="width:100%; margin-top:20px;">
                <i class="fas fa-save"></i> LƯU
            </button>
        </form>
    </div>
</div>

<!-- Modal: Confirm Split -->
<div class="modal-backdrop" id="modalConfirmSplit">
    <div class="modal modal-premium" style="max-width:400px;">
        <div class="modal-header">
            <h3><i class="fas fa-cut me-2"></i> Tách bàn</h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="font-size:0.7rem; color:var(--text-muted);">MÓN ĐÃ CHỌN</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--gold-dark); margin-top:4px;" id="modalSplitCountText">0 món</div>
            </div>
            <div style="margin-bottom:20px;">
                <div style="font-size:0.65rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; margin-bottom:8px;">BÀN ĐÍCH</div>
                <select id="splitTargetTableId" style="width:100%; padding:12px; border:2px solid #e5e5e5; border-radius:var(--radius); font-size:0.85rem;">
                    <option value="">-- Chọn bàn trống --</option>
                    <?php foreach ($grouped as $area => $tbls): ?>
                        <optgroup label="<?= e($area) ?>">
                            <?php foreach ($tbls as $t): ?>
                                <?php if ($t['status'] === 'available' && empty($t['parent_id']) && $t['id'] != ($table['id'] ?? 0)): ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= $t['capacity'] ?> chỗ)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                    <option value="0">-- Tách ra bàn mới --</option>
                </select>
            </div>
            <div style="margin-bottom:24px;">
                <div style="font-size:0.65rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; margin-bottom:8px;">KHÁCH (BÀN MỚI)</div>
                <div style="display:grid; grid-template-columns:repeat(6, 1fr); gap:8px;">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <label style="padding:10px; background:<?= $i == 2 ? 'var(--gold)' : '#f8fafc' ?>; border:2px solid <?= $i == 2 ? 'var(--gold)' : '#e5e5e5' ?>; border-radius:var(--radius); text-align:center; cursor:pointer;">
                            <input type="radio" name="split_guest_count" value="<?= $i ?>" <?= $i == 2 ? 'checked' : '' ?> style="display:none">
                            <span style="font-size:0.85rem; font-weight:700; color:<?= $i == 2 ? '#fff' : 'var(--text)' ?>;"><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <button class="bill-btn primary" onclick="submitSplitOrder()" style="width:100%;">
                <i class="fas fa-cut"></i> XÁC NHẬN TÁCH
            </button>
        </div>
    </div>
</div>

<!-- Modal: Item Note -->
<div class="modal-backdrop" id="modalItemNote" style="display:none;">
    <div class="modal modal-premium" style="max-width:380px;">
        <div class="modal-header">
            <h3><i class="fas fa-pen me-2"></i> Ghi chú món</h3>
            <button class="modal-close" onclick="closeNoteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="note-item-name" style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:16px;"></div>
            <div id="note-opts-container" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;"></div>
            <div>
                <div style="font-size:0.65rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; margin-bottom:8px;">GHI CHÚ TỰ DO</div>
                <input type="text" id="note-custom-text" placeholder="VD: Không hành, chín kỹ..." maxlength="120" style="width:100%; padding:12px; border:2px solid #e5e5e5; border-radius:var(--radius); font-size:0.85rem;">
            </div>
            <button type="button" onclick="submitItemNote()" id="btn-save-note" class="bill-btn primary" style="width:100%; margin-top:20px;">
                <i class="fas fa-check"></i> LƯU
            </button>
        </div>
    </div>
</div>

<!-- Modal: Merge Tables -->
<div class="modal-backdrop" id="modalMergeAreaFromOrder">
    <div class="modal modal-premium" style="max-width:360px;">
        <div class="modal-header">
            <h3><i class="fas fa-object-group me-2"></i> Ghép bàn</h3>
            <button class="modal-close" data-modal-close><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" action="<?= BASE_URL ?>/tables/merge" class="modal-body">
            <input type="hidden" name="parent_id" value="<?= $table['id'] ?? '' ?>">
            <input type="hidden" name="redirect" value="/orders?table_id=<?= $table['id'] ?? '' ?>&order_id=<?= $order['id'] ?? '' ?>">
            <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:16px;">
                Ghép bàn trống cùng khu vực với <strong><?= e($table['name']) ?></strong>
            </div>
            <select name="child_id" style="width:100%; padding:12px; border:2px solid #e5e5e5; border-radius:var(--radius); font-size:0.85rem;">
                <option value="">-- Chọn bàn --</option>
                <?php if (!empty($grouped)): $currentArea = $table['area'] ?? '';
                    if (isset($grouped[$currentArea])):
                        foreach ($grouped[$currentArea] as $t):
                            if ($t['status'] === 'available' && empty($t['parent_id']) && $t['id'] != ($table['id'] ?? 0)): ?>
                                <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= $t['capacity'] ?> chỗ)</option>
                            <?php endif;
                        endforeach;
                    endif;
                endif; ?>
            </select>
            <button type="submit" class="bill-btn primary" style="width:100%; margin-top:20px;">
                <i class="fas fa-link"></i> GHÉP BÀN
            </button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/index.css">

<script>
const ORDERS_CONFIG = {
    baseUrl: '<?= BASE_URL ?>',
    tableId: <?= $table['id'] ?? 0 ?>,
    orderId: <?= $order['id'] ?? 0 ?>
};
</script>

<script src="<?= BASE_URL ?>/public/js/orders/index.js"></script>