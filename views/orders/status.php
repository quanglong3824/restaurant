<?php 
// views/orders/status.php — Order Status for Customers (Bilingual)
// Xác định ngôn ngữ hiện tại
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations - Song ngữ Việt/Anh
$t = [
    'order_sent' => ['vi' => 'Đã gửi đơn hàng!', 'en' => 'Order Sent!'],
    'wait_message' => ['vi' => 'Vui lòng đợi nhân viên xác nhận và phục vụ.', 'en' => 'Please wait for staff confirmation and service.'],
    'order_details' => ['vi' => 'Chi tiết đơn hàng', 'en' => 'Order Details'],
    'order_number' => ['vi' => 'Đơn hàng #', 'en' => 'Order #'],
    'qty' => ['vi' => '', 'en' => ''], // Qty prefix empty, chỉ hiển thị số
    'total' => ['vi' => 'Tổng cộng', 'en' => 'Total'],
    'subtotal' => ['vi' => 'Tổng tiền món', 'en' => 'Subtotal'],
    'add_more' => ['vi' => 'TIẾP TỤC ĐẶT MÓN', 'en' => 'ORDER MORE'],
    'check_bill' => ['vi' => 'XEM HOÁ ĐƠN', 'en' => 'CHECK BILL'],
    'pending' => ['vi' => 'Chờ xác nhận', 'en' => 'Pending'],
    'confirmed' => ['vi' => 'Đã xác nhận', 'en' => 'Confirmed'],
    'cooking' => ['vi' => 'Đang nấu', 'en' => 'Cooking'],
    'served' => ['vi' => 'Đã phục vụ', 'en' => 'Served'],
    'cancelled' => ['vi' => 'Đã hủy', 'en' => 'Cancelled'],
    'thank_you' => ['vi' => 'Cảm ơn bạn đã đặt món!', 'en' => 'Thank you for your order!'],
    'preparing' => ['vi' => 'Bếp đang chuẩn bị món của bạn', 'en' => 'Our kitchen is preparing your dishes'],
    'estimated_time' => ['vi' => 'Thời gian dự kiến', 'en' => 'Estimated Time'],
    'minutes' => ['vi' => 'phút', 'en' => 'min'],
    'verified' => ['vi' => 'Đã xác minh', 'en' => 'Verified'],
    'order_notes' => ['vi' => 'Ghi chú', 'en' => 'Note'],
    'bilingual_qty' => ['vi' => 'SL', 'en' => 'Qty'],
];

// Helper function
function tt($key, $lang = 'vi') {
    global $t;
    return isset($t[$key][$lang]) ? $t[$key][$lang] : $key;
}
?>
<!DOCTYPE html>
<html lang="<?= $isEn ? 'en' : 'vi' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= tt('order_sent', $currentLang) ?> - Aurora Restaurant</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional inline styles for bilingual display */
        .bilingual-text {
            display: inline;
        }
        .bilingual-text .vi-text {
            color: var(--text-dark);
        }
        .bilingual-text .en-text {
            color: var(--text-light);
            font-size: 0.85em;
        }
        .bilingual-text .en-text::before {
            content: " / ";
            color: var(--border);
        }
        .lang-en .bilingual-text .vi-text {
            color: var(--text-light);
            font-size: 0.85em;
        }
        .lang-en .bilingual-text .en-text {
            color: var(--text-dark);
        }
        .lang-en .bilingual-text .vi-text::after {
            content: " / ";
            color: var(--border);
        }
        .lang-en .bilingual-text .en-text::before {
            content: "";
        }
        
        /* Modal drag handle style synced with customer.php */
        .modal-drag-handle {
            display: flex;
            justify-content: center;
            padding: 12px 0 4px;
            flex-shrink: 0;
        }
        .modal-drag-handle span {
            width: 40px;
            height: 4px;
            border-radius: 4px;
            background: #d1d5db;
            display: block;
        }
        
        /* Header icon style synced with customer.php bill modal */
        .header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px var(--gold-glow);
        }
        
        /* Status badge bilingual */
        .status-text-vi { display: <?= $isEn ? 'none' : 'inline' ?>; }
        .status-text-en { display: <?= $isEn ? 'inline' : 'none' ?>; }
        
        /* Button text style */
        .btn-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.3;
        }
        .btn-text .primary {
            font-size: 0.92rem;
            font-weight: 800;
        }
        .btn-text .secondary {
            font-size: 0.65rem;
            font-weight: 600;
            opacity: 0.7;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="<?= $isEn ? 'lang-en' : 'lang-vi' ?>">
    <div class="status-page">
        <!-- Animated Background Elements -->
        <div class="bg-decoration">
            <div class="floating-circle circle-1"></div>
            <div class="floating-circle circle-2"></div>
        </div>

        <div class="status-content">
            <!-- Success Animation Header (Modal-like) -->
            <div class="success-header">
                <!-- Drag Handle like Modal Bottom -->
                <div class="modal-drag-handle"><span></span></div>
                
                <div class="checkmark-wrapper">
                    <div class="checkmark-circle">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle-bg" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Bilingual Title -->
                <h1 class="success-title bilingual-text">
                    <span class="vi-text"><?= tt('thank_you', 'vi') ?></span>
                    <span class="en-text"><?= tt('thank_you', 'en') ?></span>
                </h1>
                
                <!-- Bilingual Subtitle -->
                <p class="success-subtitle bilingual-text">
                    <span class="vi-text"><?= tt('preparing', 'vi') ?></span>
                    <span class="en-text"><?= tt('preparing', 'en') ?></span>
                </p>
                
                <!-- Order Number Badge -->
                <div class="order-number-badge">
                    <span class="badge-label bilingual-text">
                        <span class="vi-text"><?= tt('order_number', 'vi') ?></span>
                        <span class="en-text"><?= tt('order_number', 'en') ?></span>
                    </span>
                    <span class="badge-number">#<?= e($order['id']) ?></span>
                </div>
            </div>

            <!-- Order Items Card (Modal Bill Style) -->
            <div class="order-card">
                <div class="card-header">
                    <div class="header-left">
                        <div class="header-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3 class="bilingual-text">
                            <span class="vi-text"><?= tt('order_details', 'vi') ?></span>
                            <span class="en-text"><?= tt('order_details', 'en') ?></span>
                        </h3>
                    </div>
                    <span class="order-id">#<?= e($order['id']) ?></span>
                </div>
                
                <div class="card-body">
                    <div class="items-list">
                        <?php $total = 0; ?>
                        <?php foreach ($items as $it): ?>
                            <div class="order-item">
                                <div class="item-left">
                                    <span class="item-qty"><?= $it['quantity'] ?>x</span>
                                    <div class="item-info">
                                        <?php 
                                        // Ưu tiên tiếng Anh, tiếng Việt nhỏ dưới (same as bill modal)
                                        $hasEn = !empty($it['item_name_en']);
                                        $displayName = $hasEn ? e($it['item_name_en']) : e($it['item_name']);
                                        $displayViName = $hasEn && $it['item_name'] !== $it['item_name_en'] ? e($it['item_name']) : '';
                                        ?>
                                        <span class="item-name"><?= $displayName ?></span>
                                        <?php if ($hasEn && $displayViName): ?>
                                            <span class="item-name-vi"><?= $displayViName ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($it['note'])): ?>
                                            <span class="item-note">
                                                <i class="fas fa-pen"></i>
                                                <span class="bilingual-text">
                                                    <span class="vi-text"><?= tt('order_notes', 'vi') ?>:</span>
                                                    <span class="en-text"><?= tt('order_notes', 'en') ?>:</span>
                                                </span>
                                                <?= e($it['note']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <!-- Status Badge Bilingual -->
                                        <span class="item-status status-<?= $it['status'] ?>">
                                            <i class="fas fa-<?= $it['status'] === 'confirmed' ? 'check-circle' : ($it['status'] === 'cooking' ? 'fire' : ($it['status'] === 'served' ? 'check' : ($it['status'] === 'cancelled' ? 'times-circle' : 'clock'))) ?>"></i>
                                            <span class="bilingual-text">
                                                <span class="vi-text"><?= tt($it['status'], 'vi') ?></span>
                                                <span class="en-text"><?= tt($it['status'], 'en') ?></span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <span class="item-price"><?= formatPrice($it['item_price'] * $it['quantity']) ?></span>
                            </div>
                            <?php if ($it['status'] !== 'cancelled') $total += $it['item_price'] * $it['quantity']; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="total-row">
                        <span class="total-label bilingual-text">
                            <span class="vi-text"><?= tt('subtotal', 'vi') ?></span>
                            <span class="en-text"><?= tt('subtotal', 'en') ?></span>
                        </span>
                        <span class="total-amount"><?= formatPrice($total) ?></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Modal Footer Style) -->
            <div class="action-buttons">
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span class="btn-text">
                        <span class="primary"><?= tt('add_more', $currentLang) ?></span>
                        <span class="secondary"><?= $isEn ? tt('add_more', 'vi') : tt('add_more', 'en') ?></span>
                    </span>
                </a>
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>&show_bill=1" class="btn btn-secondary">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="btn-text">
                        <span class="primary"><?= tt('check_bill', $currentLang) ?></span>
                        <span class="secondary"><?= $isEn ? tt('check_bill', 'vi') : tt('check_bill', 'en') ?></span>
                    </span>
                </a>
            </div>

            <!-- Language Switcher -->
            <div class="lang-switcher-wrapper">
                <button onclick="toggleStatusLang()" class="lang-btn">
                    <i class="fas fa-globe"></i>
                    <span><?= $isEn ? 'VI' : 'EN' ?></span>
                </button>
            </div>
        </div>

        <!-- Location Status Indicator -->
        <div id="locStatusIndicator" class="loc-indicator">
            <div class="loc-dot"></div>
            <span class="loc-text bilingual-text">
                <span class="vi-text"><?= tt('verified', 'vi') ?></span>
                <span class="en-text"><?= tt('verified', 'en') ?></span>
            </span>
        </div>
    </div>

    <script>
        const CUSTOMER_CONFIG = {
            tableId: <?= (int)$order['table_id'] ?>,
            baseUrl: '<?= BASE_URL ?>',
            currentLang: '<?= $currentLang ?>'
        };
        
        function toggleStatusLang() {
            const newLang = CUSTOMER_CONFIG.currentLang === 'vi' ? 'en' : 'vi';
            document.cookie = 'aurora_lang=' + newLang + '; path=/; max-age=31536000; SameSite=Lax';
            localStorage.setItem('aurora_lang', newLang);
            window.location.reload();
        }

        function startStatusPolling() {
            const checkStatus = async () => {
                try {
                    const res = await fetch(`${CUSTOMER_CONFIG.baseUrl}/qr/order/poll-status?t=${Date.now()}`);
                    const data = await res.json();
                    if (data.status === 'completed') {
                        window.location.href = `${CUSTOMER_CONFIG.baseUrl}/qr/thank-you`;
                    } else if (data.status === 'idle') {
                        window.location.href = `${CUSTOMER_CONFIG.baseUrl}/qr/menu?table_id=${CUSTOMER_CONFIG.tableId}`;
                    }
                } catch(e) {}
            };
            setInterval(checkStatus, 5000);
        }
        startStatusPolling();
    </script>
</body>
</html>