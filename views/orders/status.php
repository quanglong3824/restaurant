<?php 
// views/orders/status.php — Order Status for Customers
// Xác định ngôn ngữ hiện tại
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations - English only
$t = [
    'order_sent' => 'Order Sent!',
    'wait_message' => 'Please wait for staff confirmation and service.',
    'order_details' => 'Order Details',
    'order_number' => 'Order #',
    'qty' => 'Qty',
    'total' => 'Total',
    'add_more' => 'ORDER MORE',
    'check_bill' => 'CHECK BILL',
    'pending' => 'Pending',
    'confirmed' => 'Confirmed',
    'cooking' => 'Cooking',
    'served' => 'Served',
    'cancelled' => 'Cancelled',
    'thank_you' => 'Thank you for your order!',
    'preparing' => 'Our kitchen is preparing your dishes',
    'estimated_time' => 'Estimated Time',
    'minutes' => 'min',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($t['order_sent']) ?> - Aurora Restaurant</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="status-page">
        <!-- Animated Background Elements -->
        <div class="bg-decoration">
            <div class="floating-circle circle-1"></div>
            <div class="floating-circle circle-2"></div>
            <div class="floating-circle circle-3"></div>
        </div>

        <div class="status-content">
            <!-- Success Animation Header -->
            <div class="success-header">
                <div class="checkmark-wrapper">
                    <div class="checkmark-circle">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle-bg" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>
                <h1 class="success-title"><?= e($t['thank_you']) ?></h1>
                <p class="success-subtitle"><?= e($t['preparing']) ?></p>
                
                <!-- Order Number Badge -->
                <div class="order-number-badge">
                    <span class="badge-label"><?= e($t['order_number']) ?></span>
                    <span class="badge-number">#<?= e($order['id']) ?></span>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="order-card">
                <div class="card-header">
                    <div class="header-left">
                        <i class="fas fa-receipt header-icon"></i>
                        <h3><?= e($t['order_details']) ?></h3>
                    </div>
                    <span class="order-id">#<?= e($order['id']) ?></span>
                </div>
                
                <div class="card-body">
                    <div class="items-list">
                        <?php $total = 0; ?>
                        <?php foreach ($items as $it): ?>
                            <div class="order-item">
                                <div class="item-left">
                                    <span class="item-qty"><?= $t['qty'] ?><?= $it['quantity'] ?></span>
                                    <div class="item-info">
                                        <?php 
                                        // Ưu tiên tiếng Anh, tiếng Việt nhỏ dưới
                                        $hasEn = !empty($it['item_name_en']);
                                        $displayName = $hasEn ? e($it['item_name_en']) : e($it['item_name']);
                                        $displayViName = $hasEn ? e($it['item_name']) : '';
                                        ?>
                                        <span class="item-name"><?= $displayName ?></span>
                                        <?php if ($hasEn && $displayViName !== $displayName): ?>
                                            <span class="item-name-vi" style="font-size:0.75rem;color:#94a3b8;display:block;margin-top:2px;"><?= $displayViName ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($it['note'])): ?>
                                            <span class="item-note"><i class="fas fa-pen"></i> <?= e($it['note']) ?></span>
                                        <?php endif; ?>
                                        <span class="item-status status-<?= $it['status'] ?>">
                                            <i class="fas fa-<?= $it['status'] === 'confirmed' ? 'check-circle' : ($it['status'] === 'cooking' ? 'fire' : ($it['status'] === 'served' ? 'check' : 'clock')) ?>"></i>
                                            <?= $t[$it['status']] ?? $it['status'] ?>
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
                        <span class="total-label"><?= e($t['total']) ?></span>
                        <span class="total-amount"><?= formatPrice($total) ?></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span><?= e($t['add_more']) ?></span>
                </a>
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>&show_bill=1" class="btn btn-secondary">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span><?= e($t['check_bill']) ?></span>
                </a>
            </div>

            <!-- Language Switcher -->
            <div class="lang-switcher-wrapper">
                <button onclick="toggleStatusLang()" class="lang-btn">
                    <i class="fas fa-globe"></i>
                    <span>VI / EN</span>
                </button>
            </div>
        </div>

        <!-- Location Status Indicator -->
        <div id="locStatusIndicator" class="loc-indicator">
            <div class="loc-dot"></div>
            <span class="loc-text">Verified</span>
        </div>
    </div>

    <script>
        const CUSTOMER_CONFIG = {
            tableId: <?= (int)$order['table_id'] ?>,
            baseUrl: '<?= BASE_URL ?>'
        };
        
        function toggleStatusLang() {
            const currentLang = '<?= $currentLang ?>';
            const newLang = currentLang === 'vi' ? 'en' : 'vi';
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
                        // Bàn đã bị đóng nhưng không khớp session
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