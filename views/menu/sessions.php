<?php // views/menu/sessions.php — My Active Tables / Sessions Management ?>
<div class="sessions-container">
    <header class="sessions-header">
        <h1 class="playfair">ACTIVE SESSIONS</h1>
        <p class="visitor-token-label">Device ID: <code><?= substr(e($visitorToken), 0, 8) ?>...</code></p>
    </header>

    <div class="sessions-content">
        <?php if (empty($orders)): ?>
            <div class="empty-sessions">
                <i class="fas fa-qrcode"></i>
                <h3>No active sessions</h3>
                <p>Scan the QR code at your table to start ordering.</p>
                <a href="<?= BASE_URL ?>" class="btn-gold-premium mt-4">GO HOME</a>
            </div>
        <?php else: ?>
            <div class="active-orders-list">
                <?php foreach ($orders as $order): 
                    $isRoom = $order['table_type'] === 'room';
                    $total = $order['total'] ?? 0;
                ?>
                    <div class="session-card">
                        <div class="session-card-header">
                            <div class="table-badge-large <?= $isRoom ? 'room' : 'restaurant' ?>">
                                <i class="fas <?= $isRoom ? 'fa-bed' : 'fa-utensils' ?>"></i>
                                <span><?= e($order['table_name']) ?></span>
                            </div>
                            <div class="session-status occupied">Open</div>
                        </div>

                        <div class="session-card-body">
                            <div class="session-info-row">
                                <span class="label">Started:</span>
                                <span class="value"><?= date('H:i, d/m', strtotime($order['opened_at'])) ?></span>
                            </div>
                            <div class="session-info-row">
                                <span class="label">Subtotal:</span>
                                <span class="value price"><?= formatPrice($total) ?></span>
                            </div>
                        </div>

                        <div class="session-card-footer">
                            <a href="<?= BASE_URL ?>/qr/menu?table_id=<?= $order['table_id'] ?>&token=<?= $order['qr_hash'] ?>" class="btn-enter-menu">
                                BACK TO ORDER <i class="fas fa-chevron-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <p class="sessions-footer-note">
                <i class="fas fa-info-circle me-1"></i> 
                The system automatically remembers the tables you have scanned with this device.
            </p>
        <?php endif; ?>
    </div>
</div>

<style>
.sessions-container {
    padding: 20px;
    padding-bottom: 100px;
    min-height: 100vh;
    background: #f8fafc;
}
.sessions-header {
    text-align: center;
    margin-bottom: 30px;
    padding-top: 20px;
}
.sessions-header h1 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 8px;
    letter-spacing: 2px;
}
.visitor-token-label {
    font-size: 0.75rem;
    color: #64748b;
}
.visitor-token-label code {
    background: #e2e8f0;
    padding: 2px 6px;
    border-radius: 4px;
}

.empty-sessions {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.empty-sessions i { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; }
.empty-sessions h3 { font-weight: 800; color: #1e293b; margin-bottom: 10px; }
.empty-sessions p { color: #64748b; font-size: 0.9rem; }

.active-orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.session-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    border: 1px solid #f1f5f9;
}

.session-card-header {
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f8fafc;
}

.table-badge-large {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    border-radius: 12px;
    font-weight: 800;
}
.table-badge-large.restaurant { background: rgba(197, 160, 89, 0.1); color: #a68341; }
.table-badge-large.room { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }
.table-badge-large i { font-size: 1.2rem; }
.table-badge-large span { font-size: 1.1rem; }

.session-status {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 50px;
}
.session-status.occupied { background: #10b981; color: #fff; }

.session-card-body {
    padding: 20px;
}
.session-info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}
.session-info-row .label { color: #94a3b8; font-size: 0.85rem; }
.session-info-row .value { color: #1e293b; font-weight: 700; }
.session-info-row .value.price { color: #a68341; font-size: 1.1rem; }

.session-card-footer {
    padding: 15px 20px;
    background: #fcfcfd;
}
.btn-enter-menu {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 14px;
    background: #1e293b;
    color: #fff;
    text-decoration: none !important;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.btn-enter-menu:active { transform: scale(0.98); background: #0f172a; }

.sessions-footer-note {
    text-align: center;
    margin-top: 30px;
    font-size: 0.75rem;
    color: #94a3b8;
    padding: 0 40px;
    line-height: 1.6;
}
</style>
