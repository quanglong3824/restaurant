<?php
// views/orders/customer_history.php — Customer Order History (English Only)
?>
<div class="customer-history-wrapper animate-fade-in">
    <div class="history-header-section">
        <div class="brand-logo">
            <h1 class="playfair">AURORA</h1>
            <span>ORDER HISTORY</span>
        </div>
        <div class="table-badge">
            <i class="fas <?= $isRoomService ? 'fa-bed' : 'fa-utensils' ?>"></i>
            <span><?= $isRoomService ? 'ROOM' : 'TABLE' ?> <?= e($table['name']) ?></span>
        </div>
    </div>

    <div class="history-content">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>No order history</h3>
                <p class="text-muted">Paid orders will be shown here</p>
                <button class="btn-back-menu" onclick="window.location.href='<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $token ?>'">
                    <i class="fas fa-arrow-left me-2"></i>
                    BACK TO MENU
                </button>
            </div>
        <?php else: ?>
            <div class="orders-timeline">
                <?php foreach ($orders as $order): ?>
                    <div class="order-timeline-item <?= $order['id'] == $currentOrderId ? 'current' : '' ?>">
                        <div class="timeline-marker">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="order-card" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order)) ?>)">
                            <div class="order-header">
                                <div class="order-id">
                                    <span class="label">ORDER #<?= $order['id'] ?></span>
                                    <?php if ($order['id'] == $currentOrderId): ?>
                                        <span class="current-badge">OPEN</span>
                                    <?php else: ?>
                                        <span class="closed-badge">PAID</span>
                                    <?php endif; ?>
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
                                    <div class="more-items">+<?= $itemCount - 3 ?> other items</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="order-footer">
                                <div class="total-amount">
                                    <span class="label">Total</span>
                                    <span class="amount"><?= formatPrice($order['total']) ?></span>
                                </div>
                                <div class="view-detail-btn">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="action-buttons">
                <button class="btn-back-menu" onclick="window.location.href='<?= BASE_URL ?>/qr/menu?table_id=<?= $table['id'] ?>&token=<?= $token ?>'">
                    <i class="fas fa-utensils me-2"></i>
                    CONTINUE ORDERING
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-backdrop" id="orderDetailModal" style="display:none;">
    <div class="modal modal-bottom modal-premium">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice me-2"></i> Order Details</h3>
            <button class="modal-close" onclick="closeOrderDetail()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="orderDetailContent">
        </div>
        <div class="modal-footer">
            <button class="btn-sheet-close" onclick="closeOrderDetail()">
                Close
            </button>
        </div>
    </div>
</div>

<style>
.customer-history-wrapper {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
    padding-bottom: 2rem;
}

.history-header-section {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid var(--gold, #d4af37);
    gap: 10px;
}

.brand-logo {
    display: flex;
    flex-direction: column;
}

.brand-logo h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #fff;
    margin: 0;
    font-weight: 800;
}

.brand-logo span {
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: var(--gold, #d4af37);
    text-transform: uppercase;
}

.table-badge {
    background: rgba(212, 175, 55, 0.15);
    border: 1px solid rgba(212, 175, 55, 0.3);
    padding: 8px 14px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 0.8rem;
    color: var(--gold, #d4af37);
}

.history-content {
    padding: 1.5rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 1.5rem;
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e1;
    opacity: 0.5;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-state .text-muted {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.btn-back-menu {
    background: linear-gradient(135deg, var(--gold, #d4af37), var(--gold-dark, #a68341));
    color: #fff;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);
    transition: all 0.3s;
}

.btn-back-menu:active {
    transform: scale(0.98);
}

.orders-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-timeline-item {
    position: relative;
    padding-left: 2rem;
}

.order-timeline-item.current .timeline-marker {
    background: var(--gold, #d4af37);
    color: #fff;
    animation: pulse-gold 2s infinite;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 20px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

@keyframes pulse-gold {
    0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
}

.order-card {
    background: #fff;
    border-radius: 16px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    transition: all 0.3s;
}

.order-card:active {
    transform: scale(0.98);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.order-id {
    display: flex;
    align-items: center;
    gap: 8px;
}

.order-id .label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
}

.current-badge, .closed-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
    text-transform: uppercase;
}

.current-badge {
    background: #fef3c7;
    color: #d97706;
}

.closed-badge {
    background: #d1fae5;
    color: #059669;
}

.order-time {
    font-size: 0.75rem;
    color: #94a3b8;
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
    color: var(--gold, #d4af37);
    min-width: 28px;
}

.preview-item .name {
    color: #1e293b;
    flex: 1;
}

.more-items {
    font-size: 0.75rem;
    color: #64748b;
    font-style: italic;
    padding: 4px 0;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.75rem;
    border-top: 1px dashed #e2e8f0;
}

.total-amount {
    display: flex;
    flex-direction: column;
}

.total-amount .label {
    font-size: 0.7rem;
    color: #64748b;
    text-transform: uppercase;
}

.total-amount .amount {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gold, #d4af37);
}

.view-detail-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    transition: all 0.2s;
}

.order-card:hover .view-detail-btn {
    background: var(--gold, #d4af37);
    color: #fff;
}

.action-buttons {
    text-align: center;
    margin-top: 2rem;
}

.modal-premium .order-detail-items {
    max-height: 50vh;
    overflow-y: auto;
}

.order-detail-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px dashed #e2e8f0;
}

.order-detail-item:last-child {
    border-bottom: none;
}

.detail-qty {
    width: 32px;
    height: 32px;
    background: var(--gold, #d4af37);
    color: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.detail-info {
    flex: 1;
}

.detail-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.detail-note {
    font-size: 0.75rem;
    color: #64748b;
    font-style: italic;
}

.detail-price {
    font-weight: 700;
    color: var(--gold, #d4af37);
    font-size: 0.9rem;
}
</style>

<script>
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
    html += '<span style="font-weight:700;color:var(--gold,#d4af37);font-size:1.1rem;">' + order.total_formatted + '</span>';
    html += '</div>';
    html += '</div>';
    
    html += '<h4 style="margin-bottom:1rem;font-size:0.9rem;color:#64748b;text-transform:uppercase;">Order Items</h4>';
    
    if (order.items && order.items.length > 0) {
        order.items.forEach(function(item) {
            html += '<div class="order-detail-item">';
            html += '<div class="detail-qty">' + item.quantity + '</div>';
            html += '<div class="detail-info">';
            html += '<div class="detail-name">' + (item.item_name_en || item.item_name) + '</div>';
            if (item.note) {
                html += '<div class="detail-note"><i class="fas fa-pen"></i> Note: ' + item.note + '</div>';
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
    
    document.getElementById('orderDetailModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeOrderDetail() {
    document.getElementById('orderDetailModal').style.display = 'none';
    document.body.style.overflow = '';
}

function formatPrice(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.order-timeline-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    items.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.4s ease';
        observer.observe(item);
    });
});
</script>