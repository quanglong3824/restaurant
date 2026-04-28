<?php 
// views/orders/status.php — Order Status for Customers (English Only)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Sent! - Aurora Restaurant</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
<body>
    <div class="status-page">
        <div class="bg-decoration">
            <div class="floating-circle circle-1"></div>
            <div class="floating-circle circle-2"></div>
        </div>

        <div class="status-content">
            <div class="success-header">
                <div class="modal-drag-handle"><span></span></div>
                
                <div class="checkmark-wrapper">
                    <div class="checkmark-circle">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle-bg" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>
                
                <h1 class="success-title">Thank you for your order!</h1>
                
                <p class="success-subtitle">Staff is confirming your order</p>
                
                <div class="order-number-badge">
                    <span class="badge-label">Order #</span>
                    <span class="badge-number">#<?= e($order['id']) ?></span>
                </div>
            </div>

            <div class="order-card">
                <div class="card-header">
                    <div class="header-left">
                        <div class="header-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3>Order Details</h3>
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
                                        $displayName = !empty($it['item_name_en']) ? e($it['item_name_en']) : e($it['item_name']);
                                        $displayViName = !empty($it['item_name_en']) && $it['item_name'] !== $it['item_name_en'] ? e($it['item_name']) : '';
                                        ?>
                                        <span class="item-name"><?= $displayName ?></span>
                                        <?php if ($displayViName): ?>
                                            <span class="item-name-vi"><?= $displayViName ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($it['note'])): ?>
                                            <span class="item-note">
                                                <i class="fas fa-pen"></i>
                                                Note: <?= e($it['note']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="item-status status-<?= $it['status'] ?>">
                                            <i class="fas fa-<?= $it['status'] === 'confirmed' ? 'check-circle' : ($it['status'] === 'cooking' ? 'fire' : ($it['status'] === 'served' ? 'check' : ($it['status'] === 'cancelled' ? 'times-circle' : 'clock'))) ?>"></i>
                                            <?php 
                                            $statusText = [
                                                'pending' => 'Pending',
                                                'confirmed' => 'Confirmed',
                                                'cooking' => 'Cooking',
                                                'served' => 'Served',
                                                'cancelled' => 'Cancelled'
                                            ];
                                            ?>
                                            <?= $statusText[$it['status']] ?? $it['status'] ?>
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
                        <span class="total-label">Subtotal</span>
                        <span class="total-amount"><?= formatPrice($total) ?></span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span class="btn-text">
                        <span class="primary">ORDER MORE</span>
                    </span>
                </a>
                <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $_SESSION['qr_token'] ?? '' ?>&show_bill=1" class="btn btn-secondary">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="btn-text">
                        <span class="primary">CHECK BILL</span>
                    </span>
                </a>
            </div>

            <div id="locStatusIndicator" class="loc-indicator">
                <div class="loc-dot"></div>
                <span class="loc-text">Verified</span>
            </div>
        </div>
    </div>

    <script>
        const CUSTOMER_CONFIG = {
            tableId: <?= (int)$order['table_id'] ?>,
            baseUrl: '<?= BASE_URL ?>'
        };

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