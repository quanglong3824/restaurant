<?php 
// views/orders/paid_bill.php — Premium Paid Bill View (Gogi Style)
$currentLang = $_COOKIE['aurora_lang'] ?? 'vi';
$isEn = $currentLang === 'en';
?>
<div class="paid-bill-container">
    <div class="success-banner">
        <div class="success-check">
            <i class="fas fa-check"></i>
        </div>
        <h2 class="status-title"><?= $isEn ? 'PAYMENT SUCCESSFUL / THANH TOÁN THÀNH CÔNG' : 'THANH TOÁN THÀNH CÔNG / PAYMENT SUCCESSFUL' ?></h2>
        <p class="status-subtitle"><?= $isEn ? 'Thank you for dining at Aurora Restaurant' : 'Cảm ơn Quý khách đã ủng hộ Aurora Restaurant' ?></p>
    </div>

    <div class="receipt-paper">
        <div class="receipt-header">
            <h1 class="brand-name">AURORA HOTEL PLAZA</h1>
            <p class="brand-address">253 Phạm Văn Thuận, KP2, P. Tam Hiệp, Biên Hòa, Đồng Nai</p>
            <div class="receipt-divider"></div>
            <div class="receipt-meta">
                <div class="meta-item">
                    <span><?= $isEn ? 'Table:' : 'Bàn:' ?></span>
                    <strong><?= e($table['name']) ?></strong>
                </div>
                <div class="meta-item">
                    <span><?= $isEn ? 'Bill #:' : 'Mã HĐ:' ?></span>
                    <strong>#<?= $order['id'] ?></strong>
                </div>
                <div class="meta-item">
                    <span><?= $isEn ? 'Time:' : 'Thời gian:' ?></span>
                    <strong><?= date('d/m/Y H:i', strtotime($order['closed_at'] ?? $order['updated_at'])) ?></strong>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <table class="items-table">
                <thead>
                    <tr>
                        <th><?= $isEn ? 'Item' : 'Món ăn' ?></th>
                        <th class="text-center"><?= $isEn ? 'Qty' : 'SL' ?></th>
                        <th class="text-right"><?= $isEn ? 'Amount' : 'T.Tiền' ?></th>
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
                                <div class="item-name"><?= e($item['item_name']) ?></div>
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
                <span><?= $isEn ? 'TOTAL / TỔNG CỘNG' : 'TỔNG CỘNG / TOTAL' ?></span>
                <span class="total-amount"><?= formatPrice($total) ?></span>
            </div>
            <div class="payment-info">
                <span><?= $isEn ? 'Payment:' : 'Hình thức:' ?></span>
                <strong><?= ($order['payment_method'] ?? 'cash') === 'cash' ? ($isEn ? 'Cash' : 'Tiền mặt') : ($isEn ? 'Transfer' : 'Chuyển khoản') ?></strong>
            </div>
            <div class="receipt-barcode">
                <i class="fas fa-barcode"></i>
                <p><?= $isEn ? 'See you again!' : 'Hẹn gặp lại Quý khách!' ?></p>
            </div>
        </div>
        
        <!-- Decorative receipt cut edge -->
        <div class="receipt-cut"></div>
    </div>

    <div class="beta-notice">
        <i class="fas fa-info-circle"></i>
        <div class="notice-content">
            <strong><?= $isEn ? 'NOTE:' : 'KHUYẾN NGHỊ:' ?></strong> 
            <?= $isEn ? 'System is in beta testing phase. Please <b>save receipt image</b> for reference if needed. Thank you!' : 'Hệ thống đang trong giai đoạn nâng cấp thử nghiệm. Quý khách vui lòng <b>Lưu ảnh hóa đơn</b> để đối chiếu trong trường hợp cần thiết. Xin cảm ơn!' ?>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn-save-img" onclick="captureReceipt()">
            <i class="fas fa-camera"></i> <?= $isEn ? 'SAVE RECEIPT IMAGE / LƯU ẢNH HÓA ĐƠN' : 'LƯU ẢNH HÓA ĐƠN / SAVE RECEIPT IMAGE' ?>
        </button>
        <p class="button-tip"><?= $isEn ? '* Receipt image will be saved to your photo gallery.' : '* Ảnh hóa đơn sẽ được lưu trực tiếp vào thư viện ảnh của bạn.' ?></p>
        <button class="btn-new-order" onclick="startNewOrder()">
            <i class="fas fa-plus-circle"></i> <?= $isEn ? 'START NEW ORDER / TẠO LƯỢT MỚI' : 'TẠO LƯỢT MỚI / START NEW ORDER' ?>
        </button>
        <button class="btn-exit" onclick="exitSession()">
            <i class="fas fa-sign-out-alt"></i> <?= $isEn ? 'LEAVE TABLE (EXIT) / RỜI BÀN' : 'RỜI BÀN (THOÁT) / LEAVE TABLE' ?>
        </button>
    </div>
</div>

<!-- Load html2canvas for screenshot feature -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
const isEnPaidBill = <?= $isEn ? 'true' : 'false' ?>;
const paidBillTexts = {
    creating: isEnPaidBill ? '<i class="fas fa-spinner fa-spin"></i> Creating image... / Đang tạo ảnh...' : '<i class="fas fa-spinner fa-spin"></i> ĐANG TẠO ẢNH...',
    saved: isEnPaidBill ? '<i class="fas fa-check"></i> Image saved! / Đã lưu ảnh' : '<i class="fas fa-check"></i> ĐÃ LƯU ẢNH',
    error: isEnPaidBill ? 'Cannot create receipt image. Please try again.' : 'Không thể tạo ảnh hóa đơn. Vui lòng thử lại.',
    confirmNewOrder: isEnPaidBill ? 'Do you want to start a new order for this table?' : 'Bạn muốn bắt đầu lượt gọi món mới cho bàn này?',
    confirmExit: isEnPaidBill ? 'Are you sure you want to leave and end this session?' : 'Bạn xác nhận rời bàn và kết thúc phiên làm việc?'
};

async function captureReceipt() {
    const receipt = document.querySelector('.receipt-paper');
    const btn = document.querySelector('.btn-save-img');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = paidBillTexts.creating;
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
        
        btn.innerHTML = paidBillTexts.saved;
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    } catch (err) {
        console.error('Capture error:', err);
        alert(paidBillTexts.error);
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
function startNewOrder() {
    if (!confirm(paidBillTexts.confirmNewOrder)) return;
    
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
    if (!confirm(paidBillTexts.confirmExit)) return;
    
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
