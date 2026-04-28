<?php
// views/menu/landing.php — Landing Page for Customers (English Only)
$visitorToken = $_COOKIE['qr_visitor_token'] ?? '';
$hasHistory = !empty($orders) && count($orders) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURORA HOTEL PLAZA - Restaurant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gold: #c5a059;
            --gold-dark: #a68341;
            --gold-light: rgba(197, 160, 89, 0.1);
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-med: #334155;
            --text-light: #64748b;
            --border: #e2e8f0;
            --radius: 16px;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text-dark); }
        .playfair { font-family: 'Playfair Display', serif; }

        .hero-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 50vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(212,175,55,0.1)"/></svg>');
            background-size: 30px 30px;
        }

        .hero-logo {
            position: relative;
            z-index: 1;
        }

        .hero-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--gold);
            margin-bottom: 0.5rem;
            letter-spacing: 3px;
        }

        .hero-logo p {
            color: var(--text-light);
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero-cta {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-hero {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff;
            border: none;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(197, 160, 89, 0.4);
        }

        .btn-hero-secondary {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.2);
        }

        .history-section {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i { color: var(--gold); }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-light);
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-med);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .order-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .order-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s;
            cursor: pointer;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border-color: var(--gold);
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed var(--border);
        }

        .order-id {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-id .label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
        }

        .status-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 10px;
            text-transform: uppercase;
        }

        .status-badge.open { background: #fef3c7; color: #d97706; }
        .status-badge.closed { background: #d1fae5; color: #059669; }

        .order-time {
            font-size: 0.75rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .order-items-preview {
            margin-bottom: 0.75rem;
        }

        .preview-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            padding: 4px 0;
        }

        .preview-item .qty {
            font-weight: 700;
            color: var(--gold);
            min-width: 28px;
        }

        .preview-item .name {
            color: var(--text-dark);
            flex: 1;
        }

        .order-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }

        .order-total {
            display: flex;
            flex-direction: column;
        }

        .order-total .label {
            font-size: 0.7rem;
            color: var(--text-light);
            text-transform: uppercase;
        }

        .order-total .amount {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gold);
        }

        .view-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--gold-light);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .order-card:hover .view-btn {
            background: var(--gold);
            color: #fff;
        }

        .info-box {
            background: linear-gradient(135deg, var(--gold-light), rgba(197, 160, 89, 0.05));
            border: 1px solid var(--gold);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }

        .info-box i {
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .info-box h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .info-box p {
            color: var(--text-med);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(5px);
            z-index: 2000;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-backdrop.show { display: flex; }

        .modal {
            background: var(--card-bg);
            border-radius: 20px 20px 0 0;
            width: 100%;
            max-width: 600px;
            max-height: 85vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--bg);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .modal-body {
            padding: 1rem;
            overflow-y: auto;
            flex: 1;
        }

        .order-detail-items {
            max-height: 40vh;
            overflow-y: auto;
        }

        .order-detail-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
        }

        .order-detail-item:last-child { border-bottom: none; }

        .detail-qty {
            width: 32px;
            height: 32px;
            background: var(--gold);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .detail-info { flex: 1; }
        .detail-name { font-weight: 600; color: var(--text-dark); margin-bottom: 4px; }
        .detail-note { font-size: 0.75rem; color: var(--text-light); font-style: italic; }
        .detail-price { font-weight: 700; color: var(--gold); font-size: 0.9rem; }

        @media (max-width: 480px) {
            .hero-logo h1 { font-size: 2rem; }
            .hero-section { min-height: 40vh; padding: 1.5rem; }
            .history-section { padding: 1rem; }
        }
    </style>
</head>
<body>
    <section class="hero-section">
        <div class="hero-logo">
            <h1 class="playfair">AURORA</h1>
            <p>HOTEL PLAZA - RESTAURANT</p>
        </div>
        <div class="hero-cta">
            <a href="<?= BASE_URL ?>/qr/menu" class="btn-hero btn-hero-primary">
                <i class="fas fa-utensils"></i>
                <span>ORDER NOW</span>
            </a>
            <?php if ($hasHistory): ?>
            <button onclick="scrollToHistory()" class="btn-hero btn-hero-secondary">
                <i class="fas fa-history"></i>
                <span>VIEW HISTORY</span>
            </button>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($hasHistory): ?>
    <section class="history-section" id="historySection">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 class="section-title" style="margin:0;">
                <i class="fas fa-history"></i>
                <span>Order History</span>
            </h2>
            <a href="<?= BASE_URL ?>/qr/menu" class="btn-hero btn-hero-secondary" style="padding:10px 18px;font-size:0.8rem;">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
        
        <div class="order-cards">
            <?php foreach ($orders as $order): ?>
            <div class="order-card" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order)) ?>)">
                <div class="order-card-header">
                    <div class="order-id">
                        <span class="label">Order #<?= $order['id'] ?></span>
                        <span class="status-badge <?= $order['status'] ?>"><?= $order['status'] === 'open' ? 'OPEN' : 'PAID' ?></span>
                    </div>
                    <div class="order-time">
                        <i class="far fa-clock"></i>
                        <span><?= date('H:i d/m/Y', strtotime($order['created_at'])) ?></span>
                    </div>
                </div>
                
                <div class="order-items-preview">
                    <?php 
                    $items = $order['items'] ?? [];
                    $itemCount = count($items);
                    for ($i = 0; $i < min(3, $itemCount); $i++):
                        $item = $items[$i];
                    ?>
                        <div class="preview-item">
                            <span class="qty"><?= $item['quantity'] ?>x</span>
                            <span class="name"><?= !empty($item['item_name_en']) ? e($item['item_name_en']) : e($item['item_name']) ?></span>
                        </div>
                    <?php endfor; ?>
                    <?php if ($itemCount > 3): ?>
                        <div class="preview-item" style="font-style: italic; color: var(--text-light);">
                            +<?= $itemCount - 3 ?> other items
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="order-card-footer">
                    <div class="order-total">
                        <span class="label">Total</span>
                        <span class="amount"><?= formatPrice($order['total']) ?></span>
                    </div>
                    <div class="view-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php else: ?>
    <section class="history-section" id="historySection">
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h3>No order history</h3>
            <p>You have no orders yet. Order now!</p>
            <a href="<?= BASE_URL ?>/qr/menu" class="btn-hero btn-hero-primary">
                <i class="fas fa-utensils"></i>
                <span>ORDER NOW</span>
            </a>
        </div>
    </section>
    <?php endif; ?>

    <section class="history-section">
        <div class="info-box">
            <i class="fas fa-qrcode"></i>
            <h3>Order at Table</h3>
            <p>
                To order, please visit the restaurant and scan the QR code on the table.<br>
                The system will automatically identify your table to serve you best.
            </p>
        </div>
    </section>

    <div class="modal-backdrop" id="orderDetailModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-file-invoice me-2"></i> Order Details</h3>
                <button class="modal-close" onclick="closeOrderDetail()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
            </div>
        </div>
    </div>

    <script>
        function scrollToHistory() {
            document.getElementById('historySection').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function showOrderDetail(order) {
            const content = document.getElementById('orderDetailContent');
            let html = '<div class="order-detail-items">';
            
            html += '<div style="background:#f8fafc;padding:12px;border-radius:12px;margin-bottom:1rem;">';
            html += '<div style="display:flex;justify-content:space-between;margin-bottom:8px;">';
            html += '<span style="color:#64748b;font-size:0.8rem;">Time</span>';
            html += '<span style="font-weight:600;">' + new Date(order.created_at).toLocaleString('en-US') + '</span>';
            html += '</div>';
            html += '<div style="display:flex;justify-content:space-between;">';
            html += '<span style="color:#64748b;font-size:0.8rem;">Total</span>';
            html += '<span style="font-weight:700;color:var(--gold);font-size:1.1rem;">' + formatPrice(order.total) + '</span>';
            html += '</div>';
            html += '</div>';
            
            html += '<h4 style="margin-bottom:1rem;font-size:0.9rem;color:#64748b;text-transform:uppercase;">Items</h4>';
            
            if (order.items && order.items.length > 0) {
                order.items.forEach(function(item) {
                    html += '<div class="order-detail-item">';
                    html += '<div class="detail-qty">' + item.quantity + '</div>';
                    html += '<div class="detail-info">';
                    html += '<div class="detail-name">' + (item.item_name_en || item.item_name) + '</div>';
                    if (item.note) {
                        html += '<div class="detail-note"><i class="fas fa-pen"></i> ' + item.note + '</div>';
                    }
                    html += '</div>';
                    html += '<div class="detail-price">' + formatPrice(item.item_price * item.quantity) + '</div>';
                    html += '</div>';
                });
            } else {
                html += '<p style="text-align:center;color:#94a3b8;padding:2rem;">No items</p>';
            }
            
            html += '</div>';
            content.innerHTML = html;
            
            document.getElementById('orderDetailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeOrderDetail() {
            document.getElementById('orderDetailModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function formatPrice(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        }

        document.getElementById('orderDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeOrderDetail();
            }
        });

        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tableId = urlParams.get('table_id');
            if (tableId) {
                localStorage.setItem('selected_table_id', tableId);
            }
        })();
    </script>
</body>
</html>