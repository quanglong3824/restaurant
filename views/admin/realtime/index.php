<?php
// views/admin/realtime/index.php — Professional Light POS-Style Monitoring
?>

<div class="pos-monitor light-theme">
    <!-- Top Command Bar -->
    <div class="command-bar">
        <div class="brand-unit">
            <span class="unit-code">SYSTEM MONITOR</span>
            <h1 class="unit-name">ĐIỀU HÀNH TRỰC TIẾP</h1>
        </div>
        
        <div class="system-stats">
            <div class="stat-item">
                <span class="label">ĐANG PHỤC VỤ</span>
                <span class="value highlight" id="statOccupied"><?= $counts['occupied'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">BÀN TRỐNG</span>
                <span class="value" id="statAvailable"><?= $counts['available'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">DOANH THU TẠM TÍNH</span>
                <span class="value gold" id="statTempRevenue">...</span>
            </div>
        </div>

        <div class="command-actions">
            <a href="<?= BASE_URL ?>/tables" class="cmd-btn primary">
                <i class="fas fa-door-open"></i>
                <span>MỞ BÀN MỚI</span>
            </a>
            <div class="sync-box">
                <div class="sync-timer">
                    <span id="reloadCount">8</span>s
                </div>
                <button onclick="refreshData()" class="refresh-circle-btn">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Monitoring Grid -->
    <div id="realtimeListContainer" class="pos-grid">
        <!-- Loader -->
        <div class="pos-loader">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <span>Đang cập nhật dữ liệu từ các bàn...</span>
        </div>
    </div>
</div>

<style>
    /* ── Root & Variables (Light Mode) ────────────────────────── */
    :root {
        --pos-bg: #f8fafc;
        --pos-card: #ffffff;
        --pos-border: #e2e8f0;
        --pos-accent: #b8860b;
        --pos-text: #1e293b;
        --pos-text-muted: #64748b;
        --pos-success: #10b981;
        --pos-warning: #f59e0b;
        --pos-danger: #ef4444;
    }

    body { background-color: var(--pos-bg); color: var(--pos-text); font-family: 'Inter', -apple-system, sans-serif; }

    /* ── Command Bar ────────────────────────────────────────── */
    .command-bar {
        display: flex; justify-content: space-between; align-items: center;
        background: #ffffff; padding: 15px 30px; border-bottom: 2px solid var(--pos-border);
        position: sticky; top: 0; z-index: 100;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .brand-unit { display: flex; flex-direction: column; }
    .unit-code { font-size: 0.65rem; font-weight: 800; color: var(--pos-text-muted); letter-spacing: 2px; }
    .unit-name { font-size: 1.1rem; font-weight: 800; margin: 0; color: var(--pos-text); }

    .system-stats { display: flex; align-items: center; gap: 30px; }
    .stat-item { display: flex; flex-direction: column; align-items: center; }
    .stat-item .label { font-size: 0.6rem; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; margin-bottom: 2px; }
    .stat-item .value { font-size: 1.4rem; font-weight: 900; color: var(--pos-text); }
    .stat-item .value.highlight { color: var(--pos-warning); }
    .stat-item .value.gold { color: var(--pos-accent); }
    .stat-divider { width: 1px; height: 35px; background: var(--pos-border); }

    .sync-box { display: flex; align-items: center; gap: 12px; }
    .command-actions { display: flex; align-items: center; gap: 16px; }
    .cmd-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px; font-size: 0.75rem; font-weight: 800;
        cursor: pointer; transition: all 0.2s; border: none; text-decoration: none;
    }
    .cmd-btn.primary {
        background: var(--pos-accent); color: #ffffff;
    }
    .cmd-btn.primary:hover { box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3); transform: translateY(-1px); }
    
    .sync-timer { 
        width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--pos-border);
        display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--pos-text-muted);
    }
    .refresh-circle-btn {
        background: #f1f5f9; border: 1px solid var(--pos-border); color: var(--pos-text);
        width: 35px; height: 35px; border-radius: 50%; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center;
    }
    .refresh-circle-btn:hover { background: var(--pos-text); color: #fff; border-color: var(--pos-text); }

    /* ── POS Grid ────────────────────────────────────────────── */
    .pos-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 25px; padding: 30px;
    }
    .pos-loader { grid-column: 1/-1; text-align: center; padding: 100px; color: var(--pos-text-muted); display: flex; flex-direction: column; gap: 15px; align-items: center; }

    /* ── POS Card ────────────────────────────────────────────── */
    .pos-card {
        background: var(--pos-card); border: 1px solid var(--pos-border);
        border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .pos-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    
    .card-header-pos {
        padding: 20px; background: #fdfdfd;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--pos-border);
    }
    .table-main-info h2 { font-size: 1.4rem; font-weight: 900; margin: 0; color: var(--pos-text); letter-spacing: -0.5px; }
    .table-sub-info { font-size: 0.75rem; color: var(--pos-text-muted); margin-top: 4px; display: flex; gap: 12px; font-weight: 500; }
    
    .status-tag { padding: 6px 12px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-tag.open { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-tag.closed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

    .card-body-pos { padding: 0; flex: 1; overflow-y: auto; max-height: 320px; }
    .pos-table { width: 100%; border-collapse: collapse; }
    .pos-table th { 
        position: sticky; top: 0; background: #f8fafc; 
        padding: 10px 20px; font-size: 0.65rem; color: var(--pos-text-muted);
        text-align: left; text-transform: uppercase; border-bottom: 1px solid var(--pos-border);
        letter-spacing: 0.5px;
    }
    .pos-table td { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
    
    .item-title { font-weight: 700; color: var(--pos-text); }
    .item-note { font-size: 0.75rem; color: var(--pos-danger); margin-top: 3px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
    .qty-badge { font-weight: 800; color: var(--pos-text); text-align: center; }
    .price-col { text-align: right; color: var(--pos-text); font-weight: 600; font-family: 'JetBrains Mono', 'Monaco', monospace; }

    .card-footer-pos {
        padding: 20px; background: #fcfcfc;
        border-top: 1px solid var(--pos-border);
    }
    .total-summary { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .summary-left { display: flex; flex-direction: column; }
    .summary-left span:first-child { font-size: 0.6rem; color: var(--pos-text-muted); font-weight: 800; text-transform: uppercase; }
    .summary-left span:last-child { font-size: 1rem; font-weight: 800; color: var(--pos-text); }
    
    .summary-right { text-align: right; }
    .total-label { font-size: 0.65rem; color: var(--pos-text-muted); font-weight: 800; display: block; margin-bottom: 2px; }
    .total-value { font-size: 1.4rem; font-weight: 900; color: var(--pos-accent); }

    .action-row { display: flex; gap: 10px; }
    .btn-pos {
        flex: 1; padding: 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 800;
        cursor: pointer; transition: all 0.2s; border: none;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
    }
    .btn-pos-primary { background: var(--pos-text); color: #ffffff; }
    .btn-pos-primary:hover { background: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .btn-pos-primary:active { transform: scale(0.98); }
    .btn-pos-gold { background: var(--pos-accent); color: #ffffff; }
    .btn-pos-gold:hover { box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3); }
    .btn-pos-outline { background: transparent; color: var(--pos-text); border: 2px solid var(--pos-border); }
    .btn-pos-outline:hover { background: var(--pos-bg); border-color: var(--pos-text); }
    
    @media (max-width: 768px) {
        .system-stats { display: none; }
        .pos-grid { grid-template-columns: 1fr; padding: 15px; }
        .command-bar { padding: 10px 20px; flex-wrap: wrap; gap: 12px; }
        .command-actions { order: 3; width: 100%; justify-content: center; }
        .action-row { flex-wrap: wrap; }
    }

    /* Custom Scrollbar for Light Theme */
    .card-body-pos::-webkit-scrollbar { width: 5px; }
    .card-body-pos::-webkit-scrollbar-track { background: transparent; }
    .card-body-pos::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .card-body-pos::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<script>
    let timerCount = 8;
    let isRefreshing = false;

    async function refreshData() {
        if (isRefreshing) return;
        isRefreshing = true;

        const btn = document.querySelector('.refresh-circle-btn');
        if (btn) btn.innerHTML = '<i class="fas fa-sync fa-spin"></i>';

        try {
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/data?t=' + Date.now());
            const data = await res.json();
            
            if (data.ok) {
                updateStats(data);
                renderPOSGrid(data.data);
            }
        } catch (err) {
            console.error('Lỗi POS Sync:', err);
        } finally {
            if (btn) btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
            isRefreshing = false;
            timerCount = 8;
        }
    }

    function updateStats(data) {
        document.getElementById('statOccupied').textContent = data.counts.occupied;
        document.getElementById('statAvailable').textContent = data.counts.available;
        
        let tempTotal = 0;
        data.data.forEach(o => { if (o.status === 'open') tempTotal += parseFloat(o.total || 0); });
        document.getElementById('statTempRevenue').textContent = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
    }

    function renderPOSGrid(orders) {
        const container = document.getElementById('realtimeListContainer');
        if (orders.length === 0) {
            container.innerHTML = `
                <div class="pos-loader">
                    <i class="fas fa-utensils fa-3x mb-3 opacity-10"></i>
                    <h3 style="font-weight:800; color:var(--pos-text);">KHÔNG CÓ DỮ LIỆU</h3>
                    <p class="small text-muted">Hệ thống đang chờ đơn hàng mới từ khách hàng...</p>
                </div>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const isClosed = (order.status === 'closed');
            const statusTag = isClosed ? 'closed' : 'open';
            const statusText = isClosed ? 'Đã thanh toán' : (order.is_idle ? 'Đang chờ gọi món' : 'Đang ăn');
            
            let idleBadge = '';
            if (order.is_idle && !isClosed) {
                const remaining = Math.max(0, 300 - order.idle_seconds);
                const min = Math.floor(remaining / 60);
                const sec = remaining % 60;
                const color = remaining < 60 ? 'var(--pos-danger)' : 'var(--pos-warning)';
                idleBadge = `<div class="idle-timer" style="color:${color}; font-weight:800; font-size:0.75rem;">
                    <i class="fas fa-clock"></i> HUỶ SAU: ${min}:${sec < 10 ? '0'+sec : sec}
                </div>`;
            }

            let rows = '';
            order.items.forEach(it => {
                rows += `
                    <tr>
                        <td>
                            <span class="item-title">${it.item_name}</span>
                            ${it.note ? `<span class="item-note"><i class="fas fa-exclamation-circle"></i> ${it.note}</span>` : ''}
                        </td>
                        <td class="qty-badge">x${it.quantity}</td>
                        <td class="price-col">${it.subtotal_fmt}</td>
                    </tr>
                `;
            });

            html += `
                <div class="pos-card" id="card-${order.id}">
                    <div class="card-header-pos">
                        <div class="table-main-info">
                            <h2>${order.full_name}</h2>
                            <div class="table-sub-info">
                                <span><i class="fas fa-user-friends me-1"></i> ${order.guest_count} khách</span>
                                <span><i class="fas fa-user-tie me-1"></i> ${order.waiter_name || 'Khách QR'}</span>
                            </div>
                            ${idleBadge}
                        </div>
                        <div class="status-tag ${statusTag}">${statusText}</div>
                    </div>
                    
                    <div class="card-body-pos">
                        <table class="pos-table">
                            <thead>
                                <tr>
                                    <th>Món gọi</th>
                                    <th>SL</th>
                                    <th style="text-align:right">Tạm tính</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer-pos">
                        <div class="total-summary">
                            <div class="summary-left">
                                <span>GIỜ MỞ BÀN</span>
                                <span>${order.opened_at_fmt}</span>
                            </div>
                            <div class="summary-right">
                                <span class="total-label">TỔNG CỘNG</span>
                                <span class="total-value">${order.total_fmt}</span>
                            </div>
                        </div>
                        <div class="action-row">
                            ${isClosed 
                                ? `<button onclick="dismissOrder(${order.id})" class="btn-pos btn-pos-primary">
                                    <i class="fas fa-archive"></i> LƯU TRỮ
                                   </button>`
                                : `<a href="<?= BASE_URL ?>/orders?table_id=${order.table_id}&order_id=${order.id}" class="btn-pos btn-pos-outline">
                                    <i class="fas fa-eye"></i> CHI TIẾT
                                   </a>
                                   <a href="<?= BASE_URL ?>/menu?table_id=${order.table_id}&order_id=${order.id}" class="btn-pos btn-pos-outline">
                                    <i class="fas fa-plus"></i> THÊM MÓN
                                   </a>
                                   <a href="<?= BASE_URL ?>/orders?table_id=${order.table_id}&order_id=${order.id}" class="btn-pos btn-pos-gold">
                                    <i class="fas fa-credit-card"></i> THANH TOÁN
                                   </a>`
                            }
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    async function dismissOrder(id) {
        try {
            const fd = new FormData();
            fd.append('order_id', id);
            const res = await fetch('<?= BASE_URL ?>/admin/realtime/dismiss', { method: 'POST', body: fd });
            if ((await res.json()).ok) {
                const card = document.getElementById(`card-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px) scale(0.95)';
                    setTimeout(() => refreshData(), 300);
                }
            }
        } catch (err) { console.error(err); }
    }

    setInterval(() => {
        timerCount--;
        if (timerCount <= 0) refreshData();
        const el = document.getElementById('reloadCount');
        if (el) el.textContent = timerCount;
    }, 1000);

    refreshData();
</script>
