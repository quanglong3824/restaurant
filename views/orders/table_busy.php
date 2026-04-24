<?php 
// views/orders/table_busy.php — Table Busy Notification for Customers
$currentLang = $_COOKIE['aurora_lang'] ?? 'vi';
$isEn = $currentLang === 'en';
?>
<div class="table-busy-wrapper">
    <div class="busy-header">
        <div class="busy-icon-box">
            <i class="fas fa-user-lock"></i>
        </div>
        <h2 class="playfair"><?= $isEn ? 'This table is currently occupied' : 'Bàn này đang bận / This table is occupied' ?></h2>
        <div class="table-badge"><?= $isEn ? 'TABLE' : 'BÀN' ?> <?= e($table['name'] ?? ($isEn ? 'THIS' : 'NÀY')) ?></div>
    </div>

    <div class="busy-content-card">
        <p class="busy-intro">
            <?= $isEn ? 'This table appears to be in use or your session has expired.' : 'Dường như bàn này hiện đã có khách đang sử dụng hoặc phiên làm việc của Quý khách đã hết hạn.' ?>
        </p>
        
        <div class="busy-guidelines">
            <div class="guide-item">
                <i class="fas fa-users-cog"></i>
                <div>
                    <strong><?= $isEn ? 'For guests in a group:' : 'Dành cho khách đi cùng đoàn:' ?></strong>
                    <span><?= $isEn ? 'Please share the menu with others at the table or use the device that placed the order earlier.' : 'Vui lòng xem chung thực đơn với người cùng bàn hoặc sử dụng thiết bị đã gọi món trước đó.' ?></span>
                </div>
            </div>
            
            <div class="guide-item">
                <i class="fas fa-concierge-bell"></i>
                <div>
                    <strong><?= $isEn ? 'For newly arrived guests:' : 'Dành cho khách vừa mới đến:' ?></strong>
                    <span><?= $isEn ? 'If this table is actually empty, please contact our staff to open a new session.' : 'Nếu bàn này thực tế còn trống, vui lòng liên hệ nhân viên phục vụ để được hỗ trợ mở bàn mới.' ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="busy-actions">
        <button type="button" class="btn-gold w-100 mb-3" onclick="location.reload()">
            <i class="fas fa-sync-alt me-2"></i> <?= $isEn ? 'RETRY / THỬ LẠI' : 'THỬ TẢI LẠI TRANG / RETRY' ?>
        </button>
        <button type="button" class="btn-ghost w-100" onclick="window.history.back()">
            <i class="fas fa-chevron-left me-2"></i> <?= $isEn ? 'GO BACK / QUAY LẠI' : 'QUAY LẠI / GO BACK' ?>
        </button>
    </div>

    <div class="busy-footer">
        <p>© AURORA HOTEL PLAZA — <?= $isEn ? 'Premium Experience' : 'Trải nghiệm đẳng cấp' ?></p>
    </div>
</div>

<style>
    .table-busy-wrapper {
        padding: 60px 25px;
        max-width: 450px;
        margin: 0 auto;
        text-align: center;
        animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .busy-header { margin-bottom: 40px; }
    
    .busy-icon-box {
        width: 80px; height: 80px; background: rgba(245, 158, 11, 0.1);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; color: #f59e0b; font-size: 2.2rem;
        border: 2px solid rgba(245, 158, 11, 0.2);
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.1);
    }
    
    .busy-header h2 {
        font-size: 1.8rem; font-weight: 800; color: #1e293b;
        margin-bottom: 12px; letter-spacing: -0.5px;
    }
    
    .table-badge {
        display: inline-block; padding: 6px 16px; background: #334155;
        color: white; border-radius: 50px; font-weight: 700; font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .busy-content-card {
        background: white; border-radius: 24px; padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;
        margin-bottom: 35px; text-align: left;
    }
    
    .busy-intro {
        color: #64748b; line-height: 1.6; margin-bottom: 25px;
        font-size: 0.95rem; text-align: center;
    }

    .busy-guidelines { display: flex; flex-direction: column; gap: 20px; }
    
    .guide-item { display: flex; gap: 15px; }
    .guide-item i { 
        font-size: 1.2rem; color: #d4af37; margin-top: 3px;
        width: 25px; text-align: center;
    }
    .guide-item strong { display: block; color: #1e293b; font-size: 0.9rem; margin-bottom: 3px; }
    .guide-item span { color: #64748b; font-size: 0.85rem; line-height: 1.5; display: block; }

    .btn-gold {
        background: linear-gradient(135deg, #d4af37, #b8860b);
        color: white; border: none; padding: 16px; border-radius: 16px;
        font-weight: 700; font-size: 1rem; cursor: pointer;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3); transition: all 0.3s;
    }
    .btn-gold:active { transform: scale(0.97); }
    
    .btn-ghost {
        background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;
        padding: 14px; border-radius: 16px; font-weight: 600; cursor: pointer;
        transition: all 0.3s;
    }
    .btn-ghost:active { background: #f1f5f9; }

    .busy-footer { margin-top: 40px; color: #94a3b8; font-size: 0.75rem; font-weight: 500; }
    
    .playfair { font-family: 'Playfair Display', serif; }
    .w-100 { width: 100%; }
    .mb-3 { margin-bottom: 1rem; }
    .me-2 { margin-right: 0.5rem; }
</style>
