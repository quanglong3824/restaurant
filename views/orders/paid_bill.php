<?php 
// views/orders/paid_bill.php — Premium Paid Bill View (Bilingual Vietnamese/English)
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations
$TEXT = [
    'payment_successful_vi' => 'THANH TOÁN THÀNH CÔNG',
    'payment_successful_en' => 'PAYMENT SUCCESSFUL',
    'thank_you_vi' => 'Cảm ơn bạn đã dùng bữa tại Aurora Restaurant',
    'thank_you_en' => 'Thank you for dining at Aurora Restaurant',
    'table_vi' => 'Bàn:',
    'table_en' => 'Table:',
    'bill_vi' => 'Hóa đơn #:',
    'bill_en' => 'Bill #:',
    'time_vi' => 'Thời gian:',
    'time_en' => 'Time:',
    'item_vi' => 'Món',
    'item_en' => 'Item',
    'qty_vi' => 'SL',
    'qty_en' => 'Qty',
    'amount_vi' => 'Thành tiền',
    'amount_en' => 'Amount',
    'total_vi' => 'TỔNG CỘNG',
    'total_en' => 'TOTAL',
    'payment_vi' => 'Thanh toán:',
    'payment_en' => 'Payment:',
    'cash_vi' => 'Tiền mặt',
    'cash_en' => 'Cash',
    'transfer_vi' => 'Chuyển khoản',
    'transfer_en' => 'Transfer',
    'see_you_again_vi' => 'Hẹn gặp lại!',
    'see_you_again_en' => 'See you again!',
    'note_vi' => 'THÔNG BÁO:',
    'note_en' => 'NOTE:',
    'beta_msg_vi' => 'Hệ thống đang trong giai đoạn thử nghiệm. Vui lòng <b>lưu hình hóa đơn</b> để tham khảo nếu cần. Xin cảm ơn!',
    'beta_msg_en' => 'System is in beta testing phase. Please <b>save receipt image</b> for reference if needed. Thank you!',
    'save_receipt_vi' => 'LƯU HÓA ĐƠN',
    'save_receipt_en' => 'SAVE RECEIPT IMAGE',
    'receipt_tip_vi' => '* Hình hóa đơn sẽ được lưu vào thư viện ảnh.',
    'receipt_tip_en' => '* Receipt image will be saved to your photo gallery.',
    'new_order_vi' => 'ĐẶT MÓN MỚI',
    'new_order_en' => 'START NEW ORDER',
    'leave_vi' => 'KẾT THÚC PHIÊN',
    'leave_en' => 'LEAVE TABLE',
    'creating_vi' => 'Đang tạo hình...',
    'creating_en' => 'Creating image...',
    'saved_vi' => 'Đã lưu hình!',
    'saved_en' => 'Image saved!',
    'error_vi' => 'Không thể tạo hình hóa đơn. Vui lòng thử lại.',
    'error_en' => 'Cannot create receipt image. Please try again.',
    'confirm_new_order_vi' => 'Bạn muốn đặt đơn hàng mới cho bàn này?',
    'confirm_new_order_en' => 'Do you want to start a new order for this table?',
    'confirm_exit_vi' => 'Bạn chắc chắn muốn kết thúc phiên này?',
    'confirm_exit_en' => 'Are you sure you want to leave and end this session?',
];
?>
<div class="paid-bill-container">
    <div class="success-banner">
        <div class="success-check">
            <i class="fas fa-check"></i>
        </div>
        <!-- Bilingual Title -->
        <h2 class="status-title">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['payment_successful_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['payment_successful_en'] ?></span>
        </h2>
        <!-- Bilingual Subtitle -->
        <p class="status-subtitle">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['thank_you_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['thank_you_en'] ?></span>
        </p>
    </div>

    <div class="receipt-paper">
        <div class="receipt-header">
            <h1 class="brand-name">AURORA HOTEL PLAZA</h1>
            <p class="brand-address">253 Pham Van Thuan, KP2, P. Tam Hiep, Bien Hoa, Dong Nai</p>
            <div class="receipt-divider"></div>
            <div class="receipt-meta">
                <div class="meta-item">
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['table_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['table_en'] ?></span>
                    <strong><?= e($table['name']) ?></strong>
                </div>
                <div class="meta-item">
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['bill_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['bill_en'] ?></span>
                    <strong>#<?= $order['id'] ?></strong>
                </div>
                <div class="meta-item">
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['time_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['time_en'] ?></span>
                    <strong><?= date('d/m/Y H:i', strtotime($order['closed_at'] ?? $order['updated_at'])) ?></strong>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>
                            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['item_vi'] ?></span>
                            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['item_en'] ?></span>
                        </th>
                        <th class="text-center">
                            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['qty_vi'] ?></span>
                            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['qty_en'] ?></span>
                        </th>
                        <th class="text-right">
                            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['amount_vi'] ?></span>
                            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['amount_en'] ?></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($items as $item): ?>
                        <?php 
                            if ($item['status'] === 'cancelled') continue;
                            $itemTotal = $item['item_price'] * $item['quantity'];
                            $total += $itemTotal;
                        ?>
                        <tr>
                            <td>
                                <div class="item-name"><?= $isEn && !empty($item['item_name_en']) ? e($item['item_name_en']) : e($item['item_name']) ?></div>
                                <div class="item-unit-price"><?= formatPrice($item['item_price']) ?></div>
                            </td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-right"><?= formatPrice($itemTotal) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="receipt-footer">
            <div class="receipt-divider"></div>
            <div class="total-row">
                <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['total_vi'] ?></span>
                <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['total_en'] ?></span>
                <span class="total-amount"><?= formatPrice($total) ?></span>
            </div>
            <div class="payment-info">
                <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['payment_vi'] ?></span>
                <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['payment_en'] ?></span>
                <strong><?= ($order['payment_method'] ?? 'cash') === 'cash' 
                    ? ($isEn ? $TEXT['cash_en'] : $TEXT['cash_vi']) 
                    : ($isEn ? $TEXT['transfer_en'] : $TEXT['transfer_vi']) ?></strong>
            </div>
            <div class="receipt-barcode">
                <i class="fas fa-barcode"></i>
                <p>
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['see_you_again_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['see_you_again_en'] ?></span>
                </p>
            </div>
        </div>
        
        <!-- Decorative receipt cut edge -->
        <div class="receipt-cut"></div>
    </div>

    <!-- Beta Notice -->
    <div class="beta-notice">
        <i class="fas fa-info-circle"></i>
        <div class="notice-content">
            <strong class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['note_vi'] ?></strong>
            <strong class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['note_en'] ?></strong>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"> <?= $TEXT['beta_msg_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"> <?= $TEXT['beta_msg_en'] ?></span>
        </div>
    </div>

    <!-- Language Toggle -->
    <button onclick="togglePaidBillLang()" class="lang-toggle-btn" style="width: 100%; padding: 12px; font-size: 0.9rem; border-radius: 12px; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; background: #f8fafc; color: #64748b; margin-bottom: 15px;">
        <i class="fas fa-globe me-2"></i>
        <span><?= $isEn ? '🇻🇳 Tiếng Việt' : '🇬🇧 English' ?></span>
    </button>

    <div class="action-buttons">
        <button class="btn-save-img" onclick="captureReceipt()">
            <i class="fas fa-camera"></i>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['save_receipt_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['save_receipt_en'] ?></span>
        </button>
        <p class="button-tip">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['receipt_tip_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['receipt_tip_en'] ?></span>
        </p>
        <button class="btn-new-order" onclick="startNewOrder()">
            <i class="fas fa-plus-circle"></i>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['new_order_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['new_order_en'] ?></span>
        </button>
        <button class="btn-exit" onclick="exitSession()">
            <i class="fas fa-sign-out-alt"></i>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['leave_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['leave_en'] ?></span>
        </button>
    </div>
</div>

<!-- Load html2canvas for screenshot feature -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
const paidBillLang = '<?= $currentLang ?>';
const paidBillTexts = {
    creating_vi: '<i class="fas fa-spinner fa-spin"></i> <?= $TEXT['creating_vi'] ?>',
    creating_en: '<i class="fas fa-spinner fa-spin"></i> <?= $TEXT['creating_en'] ?>',
    saved_vi: '<i class="fas fa-check"></i> <?= $TEXT['saved_vi'] ?>',
    saved_en: '<i class="fas fa-check"></i> <?= $TEXT['saved_en'] ?>',
    error_vi: '<?= $TEXT['error_vi'] ?>',
    error_en: '<?= $TEXT['error_en'] ?>',
    confirm_new_order_vi: '<?= $TEXT['confirm_new_order_vi'] ?>',
    confirm_new_order_en: '<?= $TEXT['confirm_new_order_en'] ?>',
    confirm_exit_vi: '<?= $TEXT['confirm_exit_vi'] ?>',
    confirm_exit_en: '<?= $TEXT['confirm_exit_en'] ?>'
};

function togglePaidBillLang() {
    const newLang = paidBillLang === 'vi' ? 'en' : 'vi';
    document.cookie = 'aurora_lang=' + newLang + '; path=/; max-age=31536000; SameSite=Lax';
    localStorage.setItem('aurora_lang', newLang);
    window.location.reload();
}

async function captureReceipt() {
    const receipt = document.querySelector('.receipt-paper');
    const btn = document.querySelector('.btn-save-img');
    const isEn = paidBillLang === 'en';
    const originalTextVi = '<i class="fas fa-camera"></i> <?= $TEXT['save_receipt_vi'] ?>';
    const originalTextEn = '<i class="fas fa-camera"></i> <?= $TEXT['save_receipt_en'] ?>';
    const originalText = isEn ? originalTextEn : originalTextVi;
    
    btn.innerHTML = isEn ? paidBillTexts.creating_en : paidBillTexts.creating_vi;
    btn.disabled = true;

    try {
        // Create canvas from receipt element
        const canvas = await html2canvas(receipt, {
            scale: 3, // Higher scale for better quality/printing
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        });
        
        // Convert to image and download
        const image = canvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.download = `Aurora_Receipt_#${'<?= $order['id'] ?>'}.png`;
        link.href = image;
        link.click();
        
        btn.innerHTML = isEn ? paidBillTexts.saved_en : paidBillTexts.saved_vi;
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    } catch (err) {
        console.error('Capture error:', err);
        alert(isEn ? paidBillTexts.error_en : paidBillTexts.error_vi);
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

function startNewOrder() {
    const isEn = paidBillLang === 'en';
    const confirmMsg = isEn ? paidBillTexts.confirm_new_order_en : paidBillTexts.confirm_new_order_vi;
    if (!confirm(confirmMsg)) return;
    
    fetch('<?= BASE_URL ?>/qr/session/clear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $token ?? "" ?>';
        }
    });
}

function exitSession() {
    const isEn = paidBillLang === 'en';
    const confirmMsg = isEn ? paidBillTexts.confirm_exit_en : paidBillTexts.confirm_exit_vi;
    if (!confirm(confirmMsg)) return;
    
    fetch('<?= BASE_URL ?>/qr/session/clear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Redirect to a neutral page or home
            window.location.href = 'https://google.com'; // Or your homepage
        }
    });
}
</script>

<style>
    .paid-bill-container {
        padding: 40px 15px;
        max-width: 450px;
        margin: 0 auto;
        font-family: 'Inter', sans-serif;
    }

    /* Success Banner */
    .success-banner { text-align: center; margin-bottom: 30px; }
    .success-check {
        width: 60px; height: 60px; background: #10b981; color: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 15px; font-size: 1.8rem;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }
    .status-title { font-weight: 800; color: #1e293b; font-size: 1.4rem; margin-bottom: 5px; }
    .status-subtitle { color: #64748b; font-size: 0.9rem; }

    /* Receipt Paper Effect */
    .receipt-paper {
        background: white;
        padding: 30px 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: relative;
        border-radius: 4px 4px 0 0;
    }
    .receipt-header { text-align: center; margin-bottom: 25px; }
    .brand-name { font-family: 'Playfair Display', serif; font-weight: 900; font-size: 1.2rem; color: #1e293b; letter-spacing: 1px; }
    .brand-address { font-size: 0.75rem; color: #94a3b8; margin-top: 5px; }
    
    .receipt-divider { border-top: 1px dashed #cbd5e1; margin: 20px 0; }
    
    .receipt-meta { display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem; color: #475569; }
    .meta-item { display: flex; justify-content: space-between; }
    
    .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .items-table th { text-align: left; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; padding-bottom: 10px; }
    .items-table td { padding: 12px 0; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
    
    .item-name { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
    .item-unit-price { font-size: 0.75rem; color: #94a3b8; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }

    .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .total-row span:first-child { font-weight: 800; color: #1e293b; font-size: 1rem; }
    .total-amount { font-weight: 900; color: #d4af37; font-size: 1.4rem; }
    
    .payment-info { display: flex; justify-content: space-between; font-size: 0.85rem; color: #475569; }
    
    .receipt-barcode { text-align: center; margin-top: 30px; color: #cbd5e1; }
    .receipt-barcode i { font-size: 3rem; display: block; margin-bottom: 5px; }
    .receipt-barcode p { font-size: 0.8rem; font-style: italic; color: #94a3b8; }

    .receipt-cut {
        position: absolute; bottom: -10px; left: 0; width: 100%; height: 10px;
        background-image: radial-gradient(circle at 10px 10px, transparent 10px, white 11px);
        background-size: 20px 20px; background-position: -10px -10px;
    }

    /* Beta Notice */
    .beta-notice {
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid rgba(212, 175, 55, 0.3);
        padding: 15px;
        border-radius: 12px;
        margin: 25px 0 15px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        color: #856404;
        font-size: 0.8rem;
        line-height: 1.5;
    }
    .beta-notice i { color: #d4af37; font-size: 1rem; margin-top: 2px; }
    .button-tip { font-size: 0.75rem; color: #94a3b8; text-align: center; margin-top: -5px; margin-bottom: 5px; font-style: italic; }

    /* Language Toggle Button */
    .lang-toggle-btn:hover {
        background: var(--gold, #d4af37) !important;
        color: #fff !important;
        border-color: var(--gold, #d4af37) !important;
    }

    /* Action Buttons */
    .action-buttons { margin-top: 40px; display: flex; flex-direction: column; gap: 12px; }
    .btn-save-img {
        background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 16px; border-radius: 12px;
        font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }
    .btn-save-img:active { transform: scale(0.97); }
    
    .btn-new-order {
        background: #1e293b; color: white; border: none; padding: 16px; border-radius: 12px;
        font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-new-order:active { transform: scale(0.97); }
    
    .btn-exit {
        background: white; color: #ef4444; border: 1px solid #fee2e2; padding: 14px; border-radius: 12px;
        font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-exit:active { background: #fef2f2; }
</style>